<?php

namespace App\Http\Controllers\Api\V1\Admin\Emergency;

use App\Http\Controllers\Controller;
use App\Http\Traits\MessageTrait;
use App\Models\AllowanceProgram;
use App\Models\PayrollInstallmentSchedule;
use App\Models\PayrollPaymentCycle;
use App\Models\PayrollPaymentCycleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class EmergencyPaymentCycleController extends Controller
{
    use MessageTrait;
    const BASE_URL = 'http: //mis.bhata.gov.bd/api/mfssss';
    const PUSH_API = '/push-payroll-summary';

    public function getPaymentCycle(Request $request)
    {
        $searchText = $request->query('searchText');
        $installment_no = $request->query('installment_no');
        $perPage = $request->query('perPage');
        $page = $request->query('page');

        $paymentCycle = PayrollPaymentCycle::query();
        if ($searchText) {
            $paymentCycle->where('name_en', 'LIKE', "%$searchText%");
        }
        if ($searchText) {
            $paymentCycle->where('name_bn', 'LIKE', "%$searchText%");
        }
        if ($searchText) {
            $paymentCycle->where('total_beneficiaries', 'LIKE', "%$searchText%");
        }
        if ($searchText) {
            $paymentCycle->where('sub_total_amount', 'LIKE', "%$searchText%");
        }
        if ($searchText) {
            $paymentCycle->where('total_charge', 'LIKE', "%$searchText%");
        }
        if ($searchText) {
            $paymentCycle->where('total_amount', 'LIKE', "%$searchText%");
        }
        if ($installment_no) {
            $paymentCycle->where('name_en', 'LIKE', "%$installment_no%");
        }
        if ($installment_no) {
            $paymentCycle->where('name_bn', 'LIKE', "%$installment_no%");
        }

        // if ($request->has('grievance_type')) {
        //    $paymentCycle->where('title_en', $request->grievanceType);
        //  }
        //  $paymentCycle->with('resolver', 'grievanceType', 'grievanceSubject', 'program', 'gender', 'division', 'district', 'districtPouroshova', 'cityCorporation', 'ward')
        //  ->orderBy('id', 'DESC');

        $paymentCycle->orderBy('id', 'DESC');
        // return  $paymentCycle->get();
        // $paymentCycle->with(['beneficiary', 'beneficiary.employee']);
        return $paymentCycle->paginate($perPage, ['*'], 'page', $page);

    }

    public function pushPayrollSummary($id)
    {
        try {
            // Start the transaction
            return DB::transaction(function () use ($id) {
                // Find the payment cycle record
                $paymentCycle = PayrollPaymentCycle::find($id);

                if (!$paymentCycle) {
                    throw new \Exception("Payment cycle not found.");
                }

                // Update the status
                $paymentCycle->status = 'Completed';
                $paymentCycle->save();

                // push data to the payment cycle
                $paymentCycleDetails = PayrollPaymentCycleDetail::where('payroll_payment_cycle_id', $id)->get();

                if ($paymentCycleDetails->isEmpty()) {
                    return response()->json([
                        'message' => 'No records found for the provided cycle ID.',
                        'success' => false,
                    ], 404);
                }

                $formattedDetails = $paymentCycleDetails->map(function ($detail) {
                    return [
                        'beneficiary_id' => $detail->beneficiary_id,
                        'cycle_id' => $detail->payroll_payment_cycle_id,
                        'amount' => $detail->amount,
                        'reason' => $detail->reason,
                        'summary_time' => $detail->summary_time,
                        'status' => $detail->status,
                    ];
                });

                // $response = Http::contentType('application/json')
                //   ->post(self::BASE_URL . self::PUSH_API, $formattedDetails);

                //  if ($response->successful()) {
                //      return $response->json();
                //      } else {
                //        throw new \Exception('Request failed with status ' . $response->status());
                //      }

                // Update all details related to the payment cycle
                $paymentCycleDetails = PayrollPaymentCycleDetail::where('payroll_payment_cycle_id', $id)
                    ->update(['status' => 'Completed']);

                return $paymentCycleDetails;

            });
        } catch (\Throwable $e) {
            // Handle the exception (rollback is automatically handled by DB::transaction)
            // Log the error or handle it accordingly
            \Log::error("Transaction failed: " . $e->getMessage());
            throw $e; // Re-throw the exception for higher-level handling
        }

        // $paymentCycle->PayrollPay()->update(['status' => 'Completed']);
        // return $paymentCycleDetails;
    }

    public function programWiseInstallment($event)
    {

        $program = AllowanceProgram::where('id', $event)->first();
        $installment = $program->payment_cycle;
        if ($installment) {
            $installment = PayrollInstallmentSchedule::where('payment_cycle', $installment)->get();
        }
        return $installment;
    }

    public function getPaymentCycleById($id){
        
        $paymentCycle = PayrollPaymentCycle::with('PaymentCycleDetails.beneficiary.program')->find($id);
        if (!$paymentCycle) {
            throw new \Exception("Payment cycle not found.");
        }
        return $paymentCycle;
    }

}