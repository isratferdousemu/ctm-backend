<?php

namespace App\Http\Controllers\Client;

use App\Constants\ApiKey;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Beneficiary\AccountRequest;
use App\Http\Requests\Client\Beneficiary\GetListRequest;
use App\Http\Requests\Client\Beneficiary\NomineeRequest;
use App\Http\Resources\Admin\Beneficiary\BeneficiaryResource;
use App\Http\Resources\Admin\Geographic\DistrictResource;
use App\Http\Resources\Admin\Geographic\DivisionResource;
use App\Http\Resources\Admin\Systemconfig\Allowance\AllowanceResource;
use App\Http\Services\Client\ApiService;
use App\Http\Services\Client\BeneficiaryService;
use App\Http\Traits\LocationTrait;
use App\Http\Traits\MessageTrait;
use App\Http\Traits\UserTrait;
use App\Models\AllowanceProgram;
use App\Models\ApiDataReceive;
use App\Models\Application;
use App\Models\Beneficiary;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GlobalController extends Controller
{
    use MessageTrait, UserTrait, LocationTrait;


    public function getAllProgram()
    {
        $data = AllowanceProgram::where('is_active',1)->with('lookup','addtionalfield.additional_field_value')->get();

        return AllowanceResource::collection($data)->additional([
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ]);
    }


    public function getAllDivision()
    {
        $division = Location::query()
            ->whereParentId(null)
            ->get();

        return DivisionResource::collection($division)->additional([
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ]);
    }


    public function getAllDistrictByDivisionId($division_id)
    {
        $district = Location::whereParentId($division_id)->whereType($this->district)->get();

        return DistrictResource::collection($district)->additional([
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ]);
    }


    public function getAllUnionByThanaId($thana_id)
    {
        $unions = Location::whereParentId($thana_id)->whereType($this->union)->get();

        return DistrictResource::collection($unions)->additional([
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ]);
    }


    public function getAllPouroByThanaId($upazila_id)
    {
        $pouros = Location::whereParentId($upazila_id)->whereType($this->pouro)->get();

        return DistrictResource::collection($pouros)->additional([
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ]);
    }


    public function getAllThanaByDistrictId($district_id)
    {
        $thanas = Location::whereParentId($district_id)->whereType($this->thana)->whereLocationType(2)->get();

        return DistrictResource::collection($thanas)->additional([
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ]);
    }



    public function getAllCityByDistrictId($district_id, $location_type = 3)
    {
        $cities = Location::whereParentId($district_id)->whereType($this->city)->whereLocationType($location_type)->get();

        return DistrictResource::collection($cities)->additional([
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ]);
    }


    public function getAllThanaByCityId($city_id)
    {
        $thanas = Location::whereParentId($city_id)->whereType($this->thana)->whereLocationType(3)->get();
        return DistrictResource::collection($thanas)->additional([
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ]);
    }


    public function getAllWardByUnionId($union_id)
    {
        $wards = Location::whereParentId($union_id)->whereType($this->ward)->get();

        return DistrictResource::collection($wards)->additional([
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ]);
    }


}
