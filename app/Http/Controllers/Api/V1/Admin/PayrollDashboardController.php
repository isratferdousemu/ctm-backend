<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\AllowanceProgram;
use App\Models\Payroll;
use App\Models\PayrollPaymentCycle;
use App\Models\PayrolPaymentCycle;
use Carbon\Carbon;
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

        // $programs = AllowanceProgram::with('payroll')
        //     ->where('is_active', 1)
        //     ->get();
        if ($startDate && $endDate) {
            $programs = AllowanceProgram::where('is_active', 1)
                ->with(['payroll' => function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }])
                ->get();
        } else {
            $programs = AllowanceProgram::where('is_active', 1)
                ->with(['payroll' => function ($query) use ($currentYear) {
                    $query->whereYear('created_at', $currentYear);
                }])
                ->get();
        }


        $totalPayrolls = $programs->sum(function ($program) {
            return $program->payroll->count();
        });

        $programWisePayrollData = $programs->map(function ($program) use ($totalPayrolls) {
            $payrollCount = $program->payroll->count();

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

    public function monthlyApprovedPayroll(Request $request)
    {
        try {
            $currentDate = now();
            $startDate = $currentDate->copy()->subMonths(11)->startOfMonth();
            $currentYear = Carbon::now()->year;

            $programId = $request->input('program_id');

            // Construct the base query
            $query = Payroll::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
                // ->whereBetween('created_at', [$startDate, $currentDate])
                ->whereYear('created_at', $currentYear)
                ->groupBy('year', 'month')
                ->orderBy('year', 'asc')
                ->orderBy('month', 'asc');

            // Apply program ID filter if provided
            if ($programId) {
                $query->where('program_id', $programId);
            }

            // Execute the query and get the results
            $results = $query->get();

            // Create an array of the last 12 months
            $monthlyPayrollCount = collect();
            for ($i = 1; $i <= 12; $i++) {
                $monthlyPayrollCount->push([
                    'year' => $currentYear,
                    'month' => $i,
                    'count' => 0,
                    'month_name' => Carbon::create()->month($i)->format('F')
                ]);
            }

            // Map the results to the monthly data
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
            // Handle exceptions and return a meaningful error message
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
