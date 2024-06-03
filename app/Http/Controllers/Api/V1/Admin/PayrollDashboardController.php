<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\AllowanceProgram;
use App\Models\Payroll;
use Illuminate\Http\Request;

class PayrollDashboardController extends Controller
{
    public function payrollData()
    {
        $payroll = Payroll::with('program', 'financialYear', 'allotment')
            ->whereIn('status', ['Completed', 'Rejected'])
            ->get();
        $totalCompleted = $payroll->where('status', 'Completed')->count();
        $totalRejected = $payroll->where('status', 'Rejected')->count();
        return [
            'payroll' => $payroll,
            'totalCompleted' => $totalCompleted,
            'totalRejected' => $totalRejected
        ];
    }

    public function monthlyApprovedPayroll(Request $request)
    {
        $currentDate = now();
        $startDate = $currentDate->copy()->subMonths(12);

        $programId = $request->input('program_id');

        $query = Payroll::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->whereBetween('created_at', [$startDate, $currentDate])
            ->groupBy('year', 'month')
            ->orderBy('year', 'month');

        if ($programId) {
            $query->where('program_id', $programId);
        }

        $monthlyPayrollCount = $query->get()
            ->map(function ($item) {
                return [
                    'year' => $item->year,
                    'month' => $item->month,
                    'count' => $item->count,
                    'month_name' => date("F", mktime(0, 0, 0, $item->month, 1))
                ];
            });

        return [
            'monthlyPayrollCount' => $monthlyPayrollCount
        ];
    }

    public function programWisePayroll(Request $request)
    {
        $programs = AllowanceProgram::with('payroll')
            ->where('is_active', 1)
            ->get();

        $totalPayrolls = $programs->sum(function ($program) {
            return $program->payroll->count();
        });

        $programWisePayrollData = $programs->map(function ($program) use ($totalPayrolls) {
            $payrollCount = $program->payroll->count();
            $percentage = ($totalPayrolls > 0) ? ($payrollCount / $totalPayrolls) * 100 : 0;

            return [
                'program_id' => $program->id,
                'program_name' => $program->name,
                'payroll_count' => $payrollCount,
                'percentage' => $percentage
            ];
        });

        return response()->json([
            'programWisePayrollData' => $programWisePayrollData
        ]);
    }
}
