<?php

namespace App\Http\Services\Admin\Office;

use App\Models\Location;
use App\Models\Office;
use Illuminate\Support\Facades\Auth;

class OfficeListService
{

    public function getOfficesUnderUser()
    {
        $user = Auth::user();

        $type = $user->assign_location?->type;

        return $offices = match ($type) {
            'division' => $this->getDivisionOffices($user->assign_location_id),
//            'district' => $this->getDistrictUsers($user),
//            'thana' => $this->getUpazilaUsers($user),
//            'city' => $this->getWardUsers($user),
//            default => $this->grantAllUsersList($user)
        };

        return $offices ? $offices->pluck('id') : [];
    }


    public function getDivisionOffices($locationId)
    {
        $districts = Location::whereParentId($locationId)->get();
        $city = Location::whereIn('parent_id', $districts->pluck('id'))->get();

        $childId = $districts->pluck('id')->merge($city->pluck('id'));

//        $offices = Office::

    }


}
