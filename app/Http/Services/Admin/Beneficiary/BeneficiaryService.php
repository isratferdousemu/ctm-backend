<?php

namespace App\Http\Services\Admin\Beneficiary;


use App\Models\Beneficiary;
use App\Models\Committee;
use Illuminate\Http\Request;

class BeneficiaryService
{
    public function list(Request $request): \Illuminate\Contracts\Pagination\Paginator
    {
        $program_id = $request->query('program_id');
        $division_id = $request->query('division_id');
        $district_id = $request->query('district_id');
        $city_corp_id = $request->query('city_corp_id');
        $district_pourashava_id = $request->query('district_pourashava_id');
        $upazila_id = $request->query('upazila_id');
        $pourashava_id = $request->query('pourashava_id');
        $thana_id = $request->query('thana_id');
        $union_id = $request->query('union_id');
        $ward_id = $request->query('ward_id');

        $beneficiary_id = $request->query('beneficiary_id');
        $nominee_name = $request->query('nominee_name');
        $account_number = $request->query('account_number');
        $status = $request->query('status');
        $perPage = $request->query('perPage', 10);
        $sortByColumn = $request->query('sortBy', 'created_at');
        $orderByDirection = $request->query('orderBy', 'asc');

        $query = Beneficiary::query();
        if ($program_id)
            $query = $query->where('program_id', $program_id);
        if ($division_id)
            $query = $query->where('permanent_division_id', $division_id);
        if ($district_id)
            $query = $query->where('permanent_district_id', $district_id);
        if ($city_corp_id)
            $query = $query->where('permanent_city_corp_id', $city_corp_id);
        if ($district_pourashava_id)
            $query = $query->where('permanent_district_pourashava_id', $district_pourashava_id);
        if ($upazila_id)
            $query = $query->where('permanent_upazila_id', $upazila_id);
        if ($pourashava_id)
            $query = $query->where('permanent_pourashava_id', $pourashava_id);
        if ($thana_id)
            $query = $query->where('permanent_thana_id', $thana_id);
        if ($union_id)
            $query = $query->where('permanent_union_id', $union_id);
        if ($ward_id)
            $query = $query->where('permanent_ward_id', $ward_id);

        // advance search
        if ($beneficiary_id)
            $query = $query->where('application_id', $beneficiary_id);
        if ($nominee_name)
            $query = $query->whereRaw('UPPER(nominee_en) LIKE "%' . strtoupper($nominee_name) . '%"');
        if ($account_number)
            $query = $query->where('account_number', $account_number);
        if ($status)
            $query = $query->where('status', $status);


        return $query->with('program', 'permanentLocation.parent.parent.parent')->orderBy("$sortByColumn", "$orderByDirection")->paginate($perPage);
    }

    public function detail($id)
    {
        return Beneficiary::with('program', 'location.parent.parent.parent')->findOrFail($id);
    }
}
