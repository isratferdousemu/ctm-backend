<?php

namespace App\Http\Resources\Mobile\Payroll;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentTrackingMobileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name_en" => $this->name_en,
            "name_bn" => $this->name_bn,
            "beneficiary_address" => $this->beneficiary_address(),
            "age" => $this->age,
            "date_of_birth" => $this->date_of_birth,
            "nationality" => $this->nationality,
            "email" => $this->email,
            "verification_number" => $this->verification_number,
            "payroll_details" => $this->payrollDetailsArray($this->whenLoaded('PayrollDetails')),
        ];
    }

    private function beneficiary_address()
    {
        $beneficiary_address = $this->permanent_address;
        if ($this->permanentUnion) {
            $beneficiary_address .= ', ' . $this->permanentUnion->name_en;
        } elseif ($this->permanentPourashava) {
            $beneficiary_address .= ', ' . $this->permanentPourashava->name_en;
        } elseif ($this->permanentThana) {
            $beneficiary_address .= ', ' . $this->permanentThana->name_en;
        }

        if ($this->permanentUpazila) {
            $beneficiary_address .= ', ' . $this->permanentUpazila->name_en;
        } elseif ($this->permanentCityCorporation) {
            $beneficiary_address .= ', ' . $this->permanentCityCorporation->name_en;
        } elseif ($this->permanentDistrictPourashava) {
            $beneficiary_address .= ', ' . $this->permanentDistrictPourashava->name_en;
        }

        if ($this->permanentDistrict) {
            $beneficiary_address .= ', ' . $this->permanentDistrict->name_en;
        }

        return $beneficiary_address;
    }

    private function payrollDetailsArray($payrollDetails)
    {
        if (!$payrollDetails) {
            return null;
        }
        return $payrollDetails->map(function ($detail) {
            return [
                'id' => $detail->id,
                'payroll_id' => $detail->payroll_id,
                'beneficiary_id' => $detail->beneficiary_id,
                'amount' => $detail->amount,
                'charge' => $detail->charge,
                'total_amount' => $detail->total_amount,
                'status' => $detail->status,
                'deleted_at' => $detail->deleted_at,
                'created_at' => $detail->created_at,
                'updated_at' => $detail->updated_at,
                'updated_by_id' => $detail->updated_by_id,
                'payroll' => $this->payrollArray($detail->payroll),
                'payment_cycle_details' => $this->paymentCycleDetailsArray($detail->paymentCycleDetails),
            ];
        });
    }

    private function payrollArray($payroll)
    {
        if (!$payroll) {
            return null;
        }

        return [
            'id' => $payroll->id,
            // 'program_id' => $payroll->program_id,
            // 'financial_year_id' => $payroll->financial_year_id,
            'office_id' => $payroll->office_id,
            // 'allotment_id' => $payroll->allotment_id,
            // 'installment_schedule_id' => $payroll->installment_schedule_id,
            'total_beneficiaries' => $payroll->total_beneficiaries,
            // 'sub_total_amount' => $payroll->sub_total_amount,
            // 'total_charge' => $payroll->total_charge,
            // 'total_amount' => $payroll->total_amount,
            'is_approved' => $payroll->is_approved,
            'approved_by_id' => $payroll->approved_by_id,
            'approved_at' => $payroll->approved_at,
            'approved_doc' => $payroll->approved_doc,
            'approved_note' => $payroll->approved_note,
            'is_rejected' => $payroll->is_rejected,
            'rejected_by_id' => $payroll->rejected_by_id,
            'rejected_at' => $payroll->rejected_at,
            'rejected_doc' => $payroll->rejected_doc,
            'rejected_note' => $payroll->rejected_note,
            'is_payment_cycle_generated' => $payroll->is_payment_cycle_generated,
            'payment_cycle_generated_at' => $payroll->payment_cycle_generated_at,
            'deleted_at' => $payroll->deleted_at,
            'created_by_id' => $payroll->created_by_id,
            'updated_by_id' => $payroll->updated_by_id,
            'created_at' => $payroll->created_at,
            'updated_at' => $payroll->updated_at,
            'is_submitted' => $payroll->is_submitted,
            'submitted_by_id' => $payroll->submitted_by_id,
            'submitted_at' => $payroll->submitted_at,
            'financial_year' => $this->financialYearArray($payroll->financialYear),
            'installment_schedule' => $this->installmentScheduleArray($payroll->installmentSchedule),
        ];
    }

    private function financialYearArray($financialYear)
    {
        if (!$financialYear) {
            return null;
        }

        return [
            'id' => $financialYear->id,
            'financial_year' => $financialYear->financial_year,
            'start_date' => $financialYear->start_date,
            'end_date' => $financialYear->end_date,
            'status' => $financialYear->status,
            'version' => $financialYear->version,
            // 'deleted_at' => $financialYear->deleted_at,
            // 'created_at' => $financialYear->created_at,
            // 'updated_at' => $financialYear->updated_at,
        ];
    }

    private function installmentScheduleArray($installmentSchedule)
    {
        if (!$installmentSchedule) {
            return null;
        }

        return [
            'id' => $installmentSchedule->id,
            'installment_number' => $installmentSchedule->installment_number,
            'installment_name_en' => $installmentSchedule->installment_name,
            'installment_name_bn' => $installmentSchedule->installment_name_bn,
            'payment_cycle' => $installmentSchedule->payment_cycle,
            // 'deleted_at' => $installmentSchedule->deleted_at,
            // 'created_at' => $installmentSchedule->created_at,
            // 'updated_at' => $installmentSchedule->updated_at,
        ];
    }

    private function paymentCycleDetailsArray($paymentCycleDetails)
    {
        // return $paymentCycleDetails->map(function ($detail) {
            return [
                'id' => $paymentCycleDetails->id,
                'payroll_payment_cycle_id' => $paymentCycleDetails->payroll_payment_cycle_id,
                'payroll_id' => $paymentCycleDetails->payroll_id,
                'total_amount' => $paymentCycleDetails->total_amount,
                'payroll_detail_id' => $paymentCycleDetails->payroll_detail_id,
                'beneficiary_id' => $paymentCycleDetails->beneficiary_id,
                'amount' => $paymentCycleDetails->amount,
                'charge' => $paymentCycleDetails->charge,
                'status' => $paymentCycleDetails->status,
                'deleted_at' => $paymentCycleDetails->deleted_at,
                'created_at' => $paymentCycleDetails->created_at,
                'updated_at' => $paymentCycleDetails->updated_at,
            ];
        // });
    }
}
