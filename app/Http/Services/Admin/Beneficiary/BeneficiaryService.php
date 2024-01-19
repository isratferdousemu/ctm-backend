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
        $location_id = $request->query('location_id');
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
        if ($location_id)
            $query = $query->where('current_location_id', $location_id);
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
