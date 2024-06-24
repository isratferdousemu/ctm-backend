<?php

namespace App\Http\Controllers\Api\V1\Admin\Emergency;

use App\Http\Controllers\Controller;
use App\Models\EmergencyPayrollPaymentCycle;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class EmergencySupplementaryController extends Controller
{
    public function emergencySupplementaryPayrollData(Request $request)
    {
        $query = EmergencyPayrollPaymentCycle::query();

        if (request()->has('search')) {
            $searchTerm = request('search');

            $query->where(function ($q) use ($searchTerm) {
                $q->where('cycle_id', 'like', '%' . $searchTerm . '%')
                  // ->orWhere('other_field', 'like', '%' . $searchTerm . '%')
                  ;
            });
        }

        $supplementary = $query->with([
            'CycleDetails' => function ($query) {
                $query->select(
                    'emergency_cycle_id',
                    \DB::raw('SUM(CASE WHEN status = "Failed" THEN 1 ELSE 0 END) as failed_count'),
                    \DB::raw('SUM(CASE WHEN status = "Re-Submitted" THEN 1 ELSE 0 END) as resubmitted_count'),
                    \DB::raw('SUM(CASE WHEN status IN ("Failed", "Re-Submitted") THEN 1 ELSE 0 END) as status_total')
                )->groupBy('emergency_cycle_id');
            },
        ])->paginate(request('perPage'));

        return response()->json($supplementary);
    }

    public function emergencySupplementaryPayrollShow(Request $request, $id)
    {
        $payroll = EmergencyPayrollPaymentCycle::with('CycleDetails.EmergencyBeneficiary.program','CycleDetails.EmergencyPayroll.FinancialYear','CycleDetails.EmergencyPayroll.installment')->find($id);

        if (!$payroll) {
            return response()->json(['error' => 'Payroll not found'], 404);
        }

        $cycleDetails = $payroll->CycleDetails->filter(function ($cycleDetail) {
            return $cycleDetail->status === 'Re-Submitted';
        });

        $beneficiaries = $cycleDetails->map(function ($cycleDetail) {
           // return $cycleDetail->EmergencyBeneficiary ? $cycleDetail->EmergencyBeneficiary->map(function ($beneficiary) use ($cycleDetail) {
                return [
                    'emergency_cycle_id' => $cycleDetail->emergency_cycle_id,
                    'emergency_payroll_id' => $cycleDetail->emergency_payroll_id,
                    'emergency_beneficiary_id' => $cycleDetail->emergency_beneficiary_id,
                    'financial_year' => $cycleDetail->EmergencyPayroll->FinancialYear->financial_year,
                    'installment_name_en' => $cycleDetail->EmergencyPayroll->installment->installment_name,
                    'installment_name_bn' => $cycleDetail->EmergencyPayroll->installment->installment_name_bn,
                    'name_en' => $cycleDetail->EmergencyBeneficiary->name_en,
                    'name_bn' => $cycleDetail->EmergencyBeneficiary->name_bn,
                    'program_name_en' => $cycleDetail->EmergencyBeneficiary->program->name_en ?? null,
                    'program_name_bn' => $cycleDetail->EmergencyBeneficiary->program->name_bn ?? null,
                    'date_of_brith' => $cycleDetail->EmergencyBeneficiary->date_of_brith,
                    'verification_number' => $cycleDetail->EmergencyBeneficiary->verification_number,
                    'total_amount' => $cycleDetail->total_amount,
                    'amount' => $cycleDetail->amount,
                ];
            // }) : [];
        });

        // $beneficiaries = $cycleDetails->flatMap(function ($cycleDetail) {
        //     $cycleDetail->emergency_cycle_id,
        //     $cycleDetail->emergency_payroll_id
        //     $cycleDetail->emergency_beneficiary_id
        //     $cycleDetail->EmergencyBeneficiary->name_en
        //     $cycleDetail->EmergencyBeneficiary->name_bn

        //     return $cycleDetail->EmergencyBeneficiary ? [$cycleDetail->EmergencyBeneficiary] : [];
        // });

        $paginatedBeneficiaries = $beneficiaries->paginate(10);
        return response()->json([
            'data' => $paginatedBeneficiaries
        ]);
    }
}
