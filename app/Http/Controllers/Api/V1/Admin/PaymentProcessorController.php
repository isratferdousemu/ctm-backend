<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\PayrollManagement\PaymentTrackingResource;
use App\Http\Resources\CommonResource;
use App\Http\Resources\Mobile\Payroll\PaymentTrackingMobileResource;
use App\Models\bank;
use App\Models\Beneficiary;
use App\Models\PayrollPaymentProcessor;
use App\Models\PayrollPaymentProcessorArea;
use App\Rules\UniquePaymentProcessor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;
use Illuminate\Support\Facades\Validator;


class PaymentProcessorController extends Controller
{
    public function index(Request $request)
    {

        $data = PayrollPaymentProcessor::with('bank', 'ProcessorArea', 'ProcessorArea.division', 'ProcessorArea.district', 'ProcessorArea.upazila', 'ProcessorArea.union', 'ProcessorArea.thana', 'ProcessorArea.CityCorporation', 'ProcessorArea.DistrictPourashava', 'ProcessorArea.LocationType')->latest();
        if ($request->search)
            $data = $data->where(function ($data) use ($request) {
                //Search the data by name
                $data = $data->where('name_en', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('name_bn', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('focal_phone_no', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('focal_email_address', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('processor_type', 'LIKE', '%' . $request->search . '%');
            });

        // if ($request->filter !== false) {
        $data->whereHas('ProcessorArea', function ($query) use ($request) {
            if ($request->location_type) {
                $query->where('location_type', $request->location_type);
            }
            if ($request->division_id) {
                $query->where('division_id', $request->division_id);
            }
            if ($request->district_id) {
                $query->where('district_id', $request->district_id);
            }
            if ($request->upazila_id) {
                $query->where('upazila_id', $request->upazila_id);
            }
            if ($request->city_corp_id) {
                $query->where('city_corp_id', $request->city_corp_id);
            }
            if ($request->thana_id) {
                $query->where('thana_id', $request->thana_id);
            }
            if ($request->district_pouro_id) {
                $query->where('district_pourashava_id', $request->district_pouro_id);
            }
            if ($request->union_id) {
                $query->where('union_id', $request->union_id);
            }
        });
        // }

        return $this->sendResponse($data->paginate(request('perPage')));
        // $data = $data->paginate($request->get('rows', 10));
        // return CommonResource::collection($data);
    }

    public function store(Request $request)
    {
        // $request->validate([
        //     'processor_type' => 'required',
        //     'name_en' => 'string|nullable',
        //     'name_bn' => 'string|nullable',
        //     'focal_phone' => 'required|unique:payroll_payment_processors,focal_phone_no',
        //     'focal_email' => 'required|email|unique:payroll_payment_processors,focal_email_address',
        //     'division' => 'required',
        //     'district' => 'required',
        //     'location_type' => 'required',
        //     'charge' => 'integer',
        //     new UniquePaymentProcessor($request),
        // ]);
        $request->validate([
            'processor_type' => 'required',
            'name_en' => 'string|nullable',
            'name_bn' => 'string|nullable',
            'focal_phone' => 'required|unique:payroll_payment_processors,focal_phone_no',
            'focal_email' => 'required|email|unique:payroll_payment_processors,focal_email_address',
            'division' => 'required',
            'district' => 'required',
            'location_type' => 'required|integer',
            'charge' => 'integer',
            'district_pourashava' => 'nullable|required_if:location_type,1',
            'upazila' => 'nullable|required_if:location_type,2',
            'union' => 'nullable|required_if:location_type,2',
            'city_corporation' => 'nullable|required_if:location_type,3',
            'thana' => 'nullable|required_if:location_type,3',
            // new UniquePaymentProcessor($request), // Custom validation rule
        ]);

        $exists = DB::table('payroll_payment_processors')
            ->join('payroll_payment_processor_areas', 'payroll_payment_processors.id', '=', 'payroll_payment_processor_areas.payment_processor_id')
            ->where('payroll_payment_processors.processor_type', $request->processor_type)
            ->where('payroll_payment_processors.name_en', $request->name_en)
            ->where('payroll_payment_processors.name_bn', $request->name_bn)
            ->where('payroll_payment_processor_areas.division_id', $request->division)
            ->where('payroll_payment_processor_areas.district_id', $request->district)
            ->where('payroll_payment_processor_areas.location_type', $request->location_type);

        switch ($request->location_type) {
            case 1:
                $exists->where('payroll_payment_processor_areas.district_pourashava_id', $request->district_pourashava);
                break;
            case 2:
                $exists->where('payroll_payment_processor_areas.upazila_id', $request->upazila)
                    ->where('payroll_payment_processor_areas.union_id', $request->union);
                break;
            case 3:
                $exists->where('payroll_payment_processor_areas.city_corp_id', $request->city_corporation)
                    ->where('payroll_payment_processor_areas.thana_id', $request->thana);
                break;
            default:
                return response()->json(['success' => false, 'error' => 'Invalid location type.']);
        }
        // return $exists->exists();

        if ($exists->exists()  == true) {
            return response()->json(['success' => false, 'status' => '403', 'message' => 'The combination of processor type, names, and location already exists.']);
        }

        DB::beginTransaction();
        try {


            $paymentProcessor = PayrollPaymentProcessor::create([
                'processor_type' => $request->processor_type,
                'name_en' => $request->name_en,
                'name_bn' => $request->name_bn,
                'bank_id' => $request->bank_id,
                'bank_branch_name' => $request->branch_name,
                'bank_routing_number' => $request->routing_number,
                'focal_email_address' => $request->focal_email,
                'focal_phone_no' => $request->focal_phone,
                'charge' => $request->charge,
            ]);

            PayrollPaymentProcessorArea::create([
                'payment_processor_id' => $paymentProcessor->id,
                'division_id' => $request->division,
                'district_id' => $request->district,
                'location_type' => $request->location_type,
                'city_corp_id' => $request->city_corporation,
                'district_pourashava_id' => $request->district_pourashava,
                'upazila_id' => $request->upazila,
                'sub_location_type' => null,
                'pourashava_id' => null,
                'thana_id' => $request->thana,
                'union_id' => $request->union,
                'ward_id' => null,
                'location_id' => null,
                'office_id' => null,
            ]);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Payment processor created successfully']);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        return PayrollPaymentProcessor::with('bank', 'ProcessorArea', 'ProcessorArea.division', 'ProcessorArea.district', 'ProcessorArea.upazila', 'ProcessorArea.union', 'ProcessorArea.thana', 'ProcessorArea.CityCorporation', 'ProcessorArea.DistrictPourashava', 'ProcessorArea.LocationType')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'processor_type' => 'required',
            'name_en' => 'string|nullable',
            'name_bn' => 'string|nullable',
            'focal_phone' => 'required',
            'focal_email' => 'required|email',
            'division' => 'required',
            'district' => 'required',
            'location_type' => 'required',
            'charge' => 'integer',
        ]);

        DB::beginTransaction();
        try {
            $paymentProcessor = PayrollPaymentProcessor::findOrFail($id);
            $paymentProcessor->update([
                'processor_type' => $request->processor_type,
                'name_en' => $request->name_en,
                'name_bn' => $request->name_bn,
                'bank_id' => $request->bank_id,
                'bank_branch_name' => $request->branch_name,
                'bank_routing_number' => $request->routing_number,
                'focal_email_address' => $request->focal_email,
                'focal_phone_no' => $request->focal_phone,
                'charge' => $request->charge,
            ]);

            $paymentProcessorArea = PayrollPaymentProcessorArea::where('payment_processor_id', $id)->firstOrFail();
            $paymentProcessorArea->update([
                'division_id' => $request->division,
                'district_id' => $request->district,
                'location_type' => $request->location_type,
                'city_corp_id' => $request->city_corporation,
                'district_pourashava_id' => $request->district_pourashava,
                'upazila_id' => $request->upazila,
                'sub_location_type' => null,
                'pourashava_id' => null,
                'thana_id' => $request->thana,
                'union_id' => $request->union,
                'ward_id' => null,
                'location_id' => null,
                'office_id' => null,
            ]);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Payment processor updated successfully']);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $paymentProcessor = PayrollPaymentProcessor::findOrFail($id);
        $paymentProcessor->forceDelete();

        return response()->json(['message' => 'Payment Processor deleted successfully']);
    }

    public function getBanks()
    {
        return bank::all();
    }

    public function getPaymentTrackingInfo(Request $request)
    {
        $Beneficiary = Beneficiary::with(
            'program',
            'gender',
            'currentDivision',
            'currentDistrict',
            'currentCityCorporation',
            'currentDistrictPourashava',
            'currentUpazila',
            'currentPourashava',
            'currentThana',
            'currentUnion',
            'currentWard',
            'permanentDivision',
            'permanentDistrict',
            'permanentCityCorporation',
            'permanentDistrictPourashava',
            'permanentUpazila',
            'permanentPourashava',
            'permanentThana',
            'permanentUnion',
            'permanentWard',
            'financialYear',
            'payroll',
            'PaymentCycle'
        )
            ->where('verification_number', $request->beneficiary_id)
            ->first();

        if ($Beneficiary) {
            return (new PaymentTrackingResource($Beneficiary))->additional([
                'success' => true,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Beneficiary not found'
            ], 404);
        }
    }

    public function getPaymentTrackingInfoMobile(Request $request)
    {
        $Beneficiary = Beneficiary::with(
            // 'program',
            // 'gender',
            // 'currentDivision',
            // 'currentDistrict',
            // 'currentCityCorporation',
            // 'currentDistrictPourashava',
            // 'currentUpazila',
            // 'currentPourashava',
            // 'currentThana',
            // 'currentUnion',
            // 'currentWard',
            // 'permanentDivision',
            // 'permanentDistrict',
            // 'permanentCityCorporation',
            // 'permanentDistrictPourashava',
            // 'permanentUpazila',
            // 'permanentPourashava',
            // 'permanentThana',
            // 'permanentUnion',
            // 'permanentWard',
            // 'financialYear',
            'PayrollDetails.payroll.financialYear',
            'PayrollDetails.payroll.installmentSchedule',
            'PayrollDetails.paymentCycleDetails',
            // 'PaymentCycleDetails.payrollPaymentCycle'
        )
            ->where('verification_number', $request->beneficiary_id)
            ->first();

        if ($Beneficiary) {
            return (new PaymentTrackingMobileResource($Beneficiary))->additional([
                'success' => true,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Beneficiary not found'
            ], 404);
        }
    }
}
