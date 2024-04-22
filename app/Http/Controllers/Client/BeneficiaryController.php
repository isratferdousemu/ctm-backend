<?php

namespace App\Http\Controllers\Client;

use App\Constants\ApiKey;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Beneficiary\BeneficiaryResource;
use App\Http\Services\Client\ApiService;
use App\Http\Traits\MessageTrait;
use App\Models\ApiDataReceive;
use App\Models\Application;
use App\Models\Beneficiary;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BeneficiaryController extends Controller
{
    use MessageTrait;

    public function __construct(public ApiService $apiService)
    {
    }



    public function getBeneficiariesList(Request $request)
    {
        $columns = $this->apiService->hasPermission($request, ApiKey::BENEFICIARIES_LIST);

        $beneficiaryList = $this->getList($request, $columns);

        return BeneficiaryResource::collection($beneficiaryList)->additional([
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ]);
    }


    public function getList(Request $request, $columns)
    {
        $program_id = $request->query('program_id');

        $beneficiary_id = $request->query('beneficiary_id');
        $nominee_name = $request->query('nominee_name');
        $account_number = $request->query('account_number');
        $verification_number = $request->query('nid');
        $status = $request->query('status');

        $perPage = in_array('perPage', $columns) ? $request->query('perPage') : 15;
        $page = in_array('page', $columns) ? $request->query('page') : 1;

        $sortByColumn = $request->query('sortBy', 'created_at');
        $orderByDirection = $request->query('orderBy', 'asc');

        $query = Beneficiary::query();
        if ($program_id && in_array('program_id', $columns))
            $query = $query->where('program_id', $program_id);

        $query = $this->applyLocationFilter($query, $request, $columns);

        // advance search
        if ($beneficiary_id && in_array('application_id', $columns))
            $query = $query->where('application_id', $beneficiary_id);
        if ($nominee_name && in_array('nominee_name', $columns))
            $query = $query->whereRaw('UPPER(nominee_en) LIKE "%' . strtoupper($nominee_name) . '%"');
        if ($account_number && in_array('account_number', $columns))
            $query = $query->where('account_number', $account_number);
        if ($verification_number && in_array('verification_number', $columns))
            $query = $query->where('verification_number', $verification_number);
        if ($status && in_array('status', $columns))
            $query = $query->where('status', $status);


        return $query->with('program',
            'permanentDivision',
            'permanentDistrict',
            'permanentCityCorporation',
            'permanentDistrictPourashava',
            'permanentUpazila',
            'permanentPourashava',
            'permanentThana',
            'permanentUnion',
            'permanentWard')->orderBy("$sortByColumn", "$orderByDirection")
            ->paginate($perPage, ['*'], 'page', $page);

    }



    private function applyLocationFilter($query, $request, $columns): mixed
    {
        $division_id = $request->query('division_id');
        $district_id = $request->query('district_id');
        $city_corp_id = $request->query('city_corp_id');
        $district_pourashava_id = $request->query('district_pourashava_id');
        $upazila_id = $request->query('upazila_id');
        $pourashava_id = $request->query('pourashava_id');
        $thana_id = $request->query('thana_id');
        $union_id = $request->query('union_id');
        $ward_id = $request->query('ward_id');


        if ($division_id && in_array('division_id', $columns))
            $query = $query->where('permanent_division_id', $division_id);
        if ($district_id && in_array('district_id', $columns))
            $query = $query->where('permanent_district_id', $district_id);
        if ($city_corp_id && in_array('city_corp_id', $columns))
            $query = $query->where('permanent_city_corp_id', $city_corp_id);
        if ($district_pourashava_id && in_array('district_pourashava_id', $columns))
            $query = $query->where('permanent_district_pourashava_id', $district_pourashava_id);
        if ($upazila_id && in_array('upazila_id', $columns))
            $query = $query->where('permanent_upazila_id', $upazila_id);
        if ($pourashava_id && in_array('pourashava_id', $columns))
            $query = $query->where('permanent_pourashava_id', $pourashava_id);
        if ($thana_id && in_array('thana_id', $columns))
            $query = $query->where('union_id', $thana_id);
        if ($union_id && in_array('application_id', $columns))
            $query = $query->where('permanent_union_id', $union_id);
        if ($ward_id && in_array('ward_id', $columns))
            $query = $query->where('permanent_ward_id', $ward_id);

        return $query;
    }


    public function getBeneficiaryById($beneficiary_id)
    {
        $columns = $this->apiService->hasPermission(request(), ApiKey::BENEFICIARY_BY_APPLICATION_ID);

        $beneficiary = Beneficiary::with('program')
            ->where('application_id', $beneficiary_id)
            ->first();

        if ($beneficiary) {
            return BeneficiaryResource::make($beneficiary)->additional([
                'success' => true,
                'message' => $this->fetchSuccessMessage,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $this->notFoundMessage,
        ], 404);

    }














}
