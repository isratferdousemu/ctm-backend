<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\AllowanceProgram;
use App\Models\Location;
use App\Models\Payroll;
use App\Models\PayrollPaymentCycle;
use App\Models\PayrollPaymentCycleDetail;
use App\Models\PayrollPaymentProcessor;
use App\Models\PayrolPaymentCycle;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class PayrollDashboardController extends Controller
{
    public function payrollData()
    {
        $payroll = Payroll::get();
        $totalApproved = $payroll->where('is_approved', 1)->count();
        $totalRejected = $payroll->where('is_rejected', 1)->count();
        return [
            'payroll' => $payroll,
            'totalCompleted' => $totalApproved,
            'totalRejected' => $totalRejected
        ];
    }

    public function paymentCycleStatusData()
    {
        $paymentCycle = PayrollPaymentCycle::get();
        $totalPaymentCycle = $paymentCycle->count();
        $totalProcessingIbos = $paymentCycle->where('status', 'Completed')->count();

        return [
            'total_payment_cycle' => $totalPaymentCycle,
            'total_processing' => $totalProcessingIbos
        ];
    }

    public function programWisePayroll(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $currentYear = Carbon::now()->year;

        $programs = AllowanceProgram::where('is_active', 1)
            ->with(['payroll' => function ($query) use ($startDate, $endDate, $currentYear) {
                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                } else {
                    $query->whereYear('created_at', $currentYear);
                }
                $query->with('payrollDetails');
            }])
            ->get();

        $programWisePayrollData = $programs->map(function ($program) {

            $payrollCount = $program->payroll->sum(function ($payroll) {
                return $payroll->payrollDetails->count();
            });

            return [
                'name_en' => $program->name_en,
                'name_bn' => $program->name_bn,
                'payroll_count' => $payrollCount,
            ];
        });



        return response()->json([
            'data' => $programWisePayrollData
        ]);
    }

    public function programWisePaymentCycle(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $currentYear = Carbon::now()->year;

        // if ($startDate && $endDate) {
        //     $programs = AllowanceProgram::where('is_active', 1)
        //         ->with(['payroll' => function ($query) use ($startDate, $endDate) {
        //             $query->whereBetween('payment_cycle_generated_at', [$startDate, $endDate])
        //                 ->where('is_payment_cycle_generated', 1);
        //         }])
        //         ->get();
        // } else {
        //     $programs = AllowanceProgram::where('is_active', 1)
        //         ->with(['payroll' => function ($query) use ($currentYear) {
        //             $query->whereYear('payment_cycle_generated_at', $currentYear)
        //                 ->where('is_payment_cycle_generated', 1);
        //         }])
        //         ->get();
        // }

        $programs = AllowanceProgram::where('is_active', 1)
            ->with(['payroll' => function ($query) use ($startDate, $endDate, $currentYear) {
                if ($startDate && $endDate) {
                    // $query->whereBetween('created_at', [$startDate, $endDate]);
                    $query->whereBetween('payment_cycle_generated_at', [$startDate, $endDate])
                        ->where('is_payment_cycle_generated', 1);
                } else {
                    // $query->whereYear('created_at', $currentYear);
                    $query->whereYear('created_at', $currentYear)
                        ->where('is_payment_cycle_generated', 1);
                }
                $query->with('paymentCycleDetails');
            }])
            ->get();

        $programWisePayrollData = $programs->map(function ($program) {

            $payrollCount = $program->payroll->sum(function ($payroll) {
                return $payroll->paymentCycleDetails->count();
            });

            return [
                'name_en' => $program->name_en,
                'name_bn' => $program->name_bn,
                'count' => $payrollCount,
            ];
        });

        return response()->json([
            'data' => $programWisePayrollData
        ]);
    }

    public function monthlyApprovedPayroll(Request $request)
    {
        try {
            $currentDate = now();
            $startDate = $currentDate->copy()->subMonths(11)->startOfMonth();
            $currentYear = Carbon::now()->year;

            $programId = $request->input('program_id');
            $query = Payroll::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
                // ->whereBetween('created_at', [$startDate, $currentDate])
                ->where('is_approved', 1)
                ->whereYear('created_at', $currentYear)
                ->groupBy('year', 'month')
                ->orderBy('year', 'asc')
                ->orderBy('month', 'asc');

            // $query = Payroll::selectRaw('YEAR(payrolls.created_at) as year, MONTH(payrolls.created_at) as month, COUNT(DISTINCT payrolls.id) as count')
            // ->join('payroll_details', 'payroll_details.payroll_id', '=', 'payrolls.id')
            // ->where('payrolls.is_approved', 1)
            // ->whereYear('payrolls.created_at', $currentYear)
            // ->groupBy('year', 'month')
            // ->orderBy('year', 'asc')
            // ->orderBy('month', 'asc');


            if ($programId) {
                $query->where('program_id', $programId);
            }

            $results = $query->get();

            $monthlyPayrollCount = collect();
            for ($i = 1; $i <= 12; $i++) {
                $monthlyPayrollCount->push([
                    'year' => $currentYear,
                    'month' => $i,
                    'count' => 0,
                    'month_name' => Carbon::create()->month($i)->format('F')
                ]);
            }

            $results->each(function ($item) use ($monthlyPayrollCount) {
                $monthlyPayrollCount->transform(function ($monthData) use ($item) {
                    if ($monthData['year'] == $item->year && $monthData['month'] == $item->month) {
                        $monthData['count'] = $item->count;
                    }
                    return $monthData;
                });
            });

            return [
                'data' => $monthlyPayrollCount
            ];
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // public function monthlyApprovedPayroll(Request $request) {
    //     try {
    //         $currentDate = now();
    //         $currentYear = $currentDate->year;

    //         // Define the date range from June of the current year to July of the next year
    //         $startDate = Carbon::create($currentYear, 6, 1); // June 1 of the current year
    //         $endDate = Carbon::create($currentYear + 1, 7, 31); // July 31 of the next year

    //         $programId = $request->input('program_id');

    //         $query = Payroll::selectRaw('YEAR(payrolls.created_at) as year, MONTH(payrolls.created_at) as month, COUNT(DISTINCT payrolls.id) as count')
    //             ->join('payroll_details', 'payroll_details.payroll_id', '=', 'payrolls.id')
    //             ->where('is_approved', 1)
    //             ->whereBetween('created_at', [$startDate, $endDate])
    //             ->groupBy('year', 'month')
    //             ->orderBy('year', 'asc')
    //             ->orderBy('month', 'asc');

    //         if ($programId) {
    //             $query->where('program_id', $programId);
    //         }

    //         $results = $query->get();

    //         // Initialize the collection for monthly payroll count
    //         $monthlyPayrollCount = collect();
    //         for ($i = 6; $i <= 12; $i++) {
    //             $monthlyPayrollCount->push([
    //                 'year' => $currentYear,
    //                 'month' => $i,
    //                 'count' => 0,
    //                 'month_name' => Carbon::create()->month($i)->format('F')
    //             ]);
    //         }
    //         for ($i = 1; $i <= 7; $i++) {
    //             $monthlyPayrollCount->push([
    //                 'year' => $currentYear + 1,
    //                 'month' => $i,
    //                 'count' => 0,
    //                 'month_name' => Carbon::create()->month($i)->format('F')
    //             ]);
    //         }

    //         $results->each(function ($item) use ($monthlyPayrollCount) {
    //             $monthlyPayrollCount->transform(function ($monthData) use ($item) {
    //                 if ($monthData['year'] == $item->year && $monthData['month'] == $item->month) {
    //                     $monthData['count'] = $item->count;
    //                 }
    //                 return $monthData;
    //             });
    //         });

    //         return [
    //             'data' => $monthlyPayrollCount
    //         ];
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }

    public function totalPaymentProcessor()
    {

        $locations = Location::whereType('division')->get();

        $processors = PayrollPaymentProcessor::with('ProcessorArea')->get();

        $result = [];

        $groupedByDivisionId = $processors->groupBy('ProcessorArea.division_id');

        foreach ($locations as $location) {
            $divisionId = $location->id;
            $processorsInDivision = $groupedByDivisionId->get($divisionId, collect());
            $count = $processorsInDivision->count();
            $result[] = [
                'name_en' => $location->name_en,
                'name_bn' => $location->name_bn,
                'count' => $count
            ];
        }

        return [
            'data' => $result
        ];
    }

    public function totalAmountDisbursed(Request $request)
    {
        $request->validate([
            'program_id' => 'sometimes|integer',
        ]);

        $currentYear = now()->year;

        $years = range($currentYear - 2, $currentYear + 2);

        $programId = $request->input('program_id');

        $paymentCycleDetailsQuery = PayrollPaymentCycleDetail::where('status', 'Completed')
            ->whereIn(DB::raw('YEAR(created_at)'), $years)
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(total_amount) as total_amount')
            )
            ->groupBy('year');

        if ($programId) {
            $paymentCycleDetailsQuery = $paymentCycleDetailsQuery->whereHas('payroll', function ($query) use ($programId) {
                $query->where('program_id', $programId);
            });
        }

        $paymentCycleDetails = $paymentCycleDetailsQuery->get();

        return response()->json(['data' => $paymentCycleDetails]);
    }

    public function programBalance(Request $request)
    {
        $request->validate([
            'program_id' => 'integer',
        ]);

        $programId = $request->input('program_id');
        $programsQuery = AllowanceProgram::with('programAmount');

        if ($programId) {
            $programsQuery->where('id', $programId);
        }
        $programs = $programsQuery->get();
        $totalAmount = 0;

        foreach ($programs as $program) {
            if ($program->programAmount) {
                $totalAmount += $program->programAmount->amount ?? 0;
            }
        }

        $paymentCycleDetails = PayrollPaymentCycleDetail::with('payroll')->where('status', 'Completed');
        if ($programId) {
            $paymentCycleDetails = $paymentCycleDetails->whereHas('payroll', function ($q) use ($programId) {
                $q->where('program_id', $programId);
            });
        }
        $paymentCycleDetails = $paymentCycleDetails->get();
        $totalDisbursed = 0;

        foreach ($paymentCycleDetails as $item) {
            $totalDisbursed += $item['total_amount'] ?? 0;
        }
        $remaining = $totalAmount - $totalDisbursed;

        $data = [
            [
                'name_en' => 'Total Amount',
                'name_bn' => 'মোট পরিমাণ',
                'count' => $totalAmount
            ],
            [
                'name_en' => 'Total Disbursed',
                'name_bn' => 'মোট বিতরণ',
                'count' => $totalDisbursed
            ],
            [
                'name_en' => 'Remaining',
                'name_bn' => 'অবশিষ্ট',
                'count' => $remaining
            ]
        ];
        return response()->json(['data' => $data]);
    }

    //emergency dashboard data

    public function paymentCycleDisbursementStatus(Request $request)
    {
        $programId = $request->input('program_id');

        $payrollPaymentCycleDetails = PayrollPaymentCycleDetail::with('payroll');

        if ($programId) {
            $payrollPaymentCycleDetails->whereHas('payroll', function ($query) use ($programId) {
                $query->where('program_id', $programId);
            });
        }

        $completed = (clone $payrollPaymentCycleDetails)->where('status', 'Completed')->count();
        $pending = (clone $payrollPaymentCycleDetails)->where('status', 'Pending')->count();
        $initiated = (clone $payrollPaymentCycleDetails)->where('status', 'Initiated')->count();
        $failed = (clone $payrollPaymentCycleDetails)->where('status', 'Failed')->count();

        $statusCounts = [
            ['name' => 'Completed', 'count' => $completed],
            ['name' => 'Pending', 'count' => $pending],
            ['name' => 'Initiated', 'count' => $initiated],
            ['name' => 'Failed', 'count' => $failed],
        ];

        return response()->json($statusCounts);
    }
    public function emergencyDashboardData()
    {
    }
}
