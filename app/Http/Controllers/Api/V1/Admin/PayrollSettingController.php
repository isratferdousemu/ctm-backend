<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\AllowanceProgram;
use App\Models\FinancialYear;
use App\Models\Installment;
use App\Models\PayrollInstallmentSchedule;
use App\Models\PayrollInstallmentSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PayrollSettingController extends Controller
{
    public function getFinancialYear()
    {
        return FinancialYear::where("status", 1)->first();
    }
    public function getAllAllowance()
    {
        return AllowanceProgram::where("is_active", 1)->get();
    }

    public function getAllInstallments()
    {
        return PayrollInstallmentSchedule::get();
    }

    public function payrollSettingSubmit(Request $request)
    {
        $rules = [
            'allowances' => 'required|array',
            'allowances.*.allowance_id' => 'required|integer',
            'allowances.*.selectedInstallments' => 'required|array',
            'allowances.*.selectedInstallments.*.installment_id' => 'required|integer',
            'financial_year' => 'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        \DB::beginTransaction();

        try {
           $allSettings = PayrollInstallmentSetting::withTrashed()->get();
            foreach ($allSettings as $key => $setting) {
                $setting->forceDelete();
            }
            foreach ($request->allowances as $item) {
                $allowanceId = $item['allowance_id'];
                $installments = $item['selectedInstallments'];
                foreach ($installments as $key => $value) {
                    PayrollInstallmentSetting::create([
                        'program_id' => $allowanceId,
                        'financial_year_id' => $request->financial_year,
                        'installment_schedule_id' => $value['installment_id'],
                    ]);
                }
            }

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payroll Setting Updated Successfully',
            ]);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating payroll setting',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getSettingData(Request $request)
    {
        $groupedData = PayrollInstallmentSetting::with('allowance', 'installment')
            ->get()
            ->groupBy('program_id');

        $formattedData = [];
        foreach ($groupedData as $programId => $items) {
            $installmentIds = $items->pluck('installment_schedule_id')->toArray();
            $formattedData[] = [
                'program_id' => $programId,
                'installment_ids' => $installmentIds,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $formattedData,
        ]);
    }
}
