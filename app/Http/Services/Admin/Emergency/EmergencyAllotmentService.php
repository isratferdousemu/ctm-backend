<?php

namespace App\Http\Services\Admin\Emergency;

use App\Models\EmergencyAllotment;
use Illuminate\Http\Request;


class EmergencyAllotmentService
{

    public function getEmergencyAllotments(Request $request)
    {
        $searchText = $request->query('searchText');
        $perPage = $request->query('perPage');
        $page = $request->query('page');
        $status = $request->query('status');

        $payment_name = $request->query('emergency_payment_name');
        $started_period = $request->query('started_period');
        $closing_period = $request->query('closing_period');

        $location_type = $request->query('location_type');
        $division_id = $request->query('division_id');
        $district_id = $request->query('district_id');

        $thana_id = $request->query('thana_id');
        $union_id = $request->query('union_id');
        $city_id = $request->query('city_id');
        $city_thana_id = $request->query('city_thana_id');
        $district_pouro_id = $request->query('district_pouro_id');
        $pouro_id = $request->query('pouro_id');

        $filterArrayName = [];

        $filterArrayLocationType = [];
        $filterArrayDivisionId = [];
        $filterArrayDistrictId = [];

        $filterArrayThanaId = [];
        $filterArrayUnionId = [];
        $filterArrayCityId = [];
        $filterArrayCityThanaId = [];
        $filterArrayDistrictPouroId = [];
        $filterArrayPouroId = [];
        $filterArrayStatus = [];

        if ($searchText) {
            $filterArrayName[] = ['emergency_payment_name', 'LIKE', '%' . $searchText . '%'];
        }

        if ($location_type) {
            $filterArrayLocationType[] = ['location_type', '=', $location_type];
        }
        if ($division_id) {
            $filterArrayDivisionId[] = ['division_id', '=', $division_id];
        }
        if ($district_id) {
            $filterArrayDistrictId[] = ['district_id', '=', $district_id];
        }
        if ($thana_id) {
            $filterArrayThanaId[] = ['thana_id', '=', $thana_id];
        }
        if ($union_id) {
            $filterArrayUnionId[] = ['union_id', '=', $union_id];
        }

        if ($city_id) {
            $filterArrayCityId[] = ['city_id', '=', $city_id];
        }
        if ($city_thana_id) {
            $filterArrayCityThanaId[] = ['city_thana_id', '=', $city_thana_id];
        }

        if ($district_pouro_id) {
            $filterArrayDistrictPouroId[] = ['district_pouro_id', '=', $district_pouro_id];
        }
        if ($pouro_id) {
            $filterArrayPouroId[] = ['pouro_id', '=', $pouro_id];
        }

        $query = EmergencyAllotment::query();

        $query->when($searchText, function ($q) use ($filterArrayName) {
            $q->where($filterArrayName);
        });
        $query->when($location_type, function ($q) use ($filterArrayLocationType) {
            $q->where($filterArrayLocationType);
        });
        $query->when($division_id, function ($q) use ($filterArrayDivisionId) {
            $q->where($filterArrayDivisionId);
        });
        $query->when($district_id, function ($q) use ($filterArrayDistrictId) {
            $q->where($filterArrayDistrictId);
        });

        $query->when($thana_id, function ($q) use ($filterArrayThanaId) {
            $q->where($filterArrayThanaId);
        });
        $query->when($union_id, function ($q) use ($filterArrayUnionId) {
            $q->where($filterArrayUnionId);
        });
        $query->when($city_id, function ($q) use ($filterArrayCityId) {
            $q->where($filterArrayCityId);
        });
        $query->when($city_thana_id, function ($q) use ($filterArrayCityThanaId) {
            $q->where($filterArrayCityThanaId);
        });
        $query->when($district_pouro_id, function ($q) use ($filterArrayDistrictPouroId) {
            $q->where($filterArrayDistrictPouroId);
        });
        $query->when($pouro_id, function ($q) use ($filterArrayPouroId) {
            $q->where($filterArrayPouroId);
        });

        if ($payment_name) {
            $query->where('emergency_payment_name', $payment_name);
        }

        if ($started_period && $closing_period) {
            $query->whereBetween('starting_period', [$started_period, $closing_period]);
        }
        return $query->with('program',  'division', 'district', 'upazila', 'cityCorporation', 'districtPourosova', 'location')
            ->orderBy('emergency_payment_name', 'asc')
            ->latest()
            ->paginate($perPage, ['*'], 'page');
    }

    public function edit($id)
    {
        $allotment = EmergencyAllotment::where('id', $id)->first();
        return $allotment;
    }
    public function update($request, $id)
    {

        try {

            $allotment = EmergencyAllotment::where('id', $id)->first();
            $allotment->program_id                            = $request->program_id;
            $allotment->emergency_payment_name                = $request->payment_name;
            $allotment->payment_cycle                         = $request->payment_cycle;
            $allotment->amount_per_person                     = $request->per_person_amount;
            $allotment->division_id                           = $request->division_id;
            $allotment->district_id                           = $request->district_id;
            $allotment->location_type                         = $request->location_type;
            $allotment->no_of_new_benificiariy                = $request->no_of_new_benificiary;
            $allotment->no_of_existing_benificiariy           = $request->no_of_existing_benificiary;
            $allotment->starting_period                       = $request->starting_period;
            $allotment->closing_period                        = $request->closing_period;
            $allotment->updated_by_id                         = Auth()->user()->id;

            if ($request->has('ward_id_city') && $request->ward_id_city != null) {
                $allotment->location_id = $request->ward_id_city;
            }
            if ($request->has('ward_id_dist') && $request->ward_id_dist != null) {
                $allotment->location_id = $request->ward_id_dist;
            }
            if ($request->has('ward_id_union') && $request->ward_id_union != null) {
                $allotment->location_id = $request->ward_id_union;
            }
            if ($request->has('ward_id_pouro') && $request->ward_id_pouro != null) {
                $allotment->location_id = $request->ward_id_pouro;
            }

            //Dist pouro
            if ($request->location_type == 1) {
                $allotment->district_pourashava_id = $request->district_pouro_id;
                $allotment->ward_id = $request->ward_id_dist;
            }

            //City corporation
            if ($request->location_type == 3) {
                $allotment->city_corp_id = $request->city_id;
                $allotment->thana_id = $request->city_thana_id;
                $allotment->ward_id = $request->ward_id_city;
            }
            //Upazila
            if ($request->location_type == 2) {
                $allotment->upazila_id = $request->thana_id;
                //union
                if ($request->sub_location_type == 2) {
                    $allotment->union_id = $request->union_id;
                    $allotment->ward_id = $request->ward_id_union;
                } else {
                    //pouro
                    $allotment->pourashava_id = $request->pouro_id;
                    $allotment->ward_id = $request->ward_id_pouro;
                }
            }
            $allotment->update();
            return $allotment;
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public function storeAllotment(Request $request)
    {
        try {
            $allotment = new EmergencyAllotment();
            $allotment->program_id                            = $request->program_id;
            $allotment->emergency_payment_name                = $request->payment_name;
            $allotment->payment_cycle                         = $request->payment_cycle;
            $allotment->amount_per_person                     = $request->per_person_amount;
            $allotment->division_id                           = $request->division_id;
            $allotment->district_id                           = $request->district_id;
            $allotment->location_type                         = $request->location_type;
            $allotment->no_of_new_benificiariy                = $request->no_of_new_benificiary;
            $allotment->no_of_existing_benificiariy           = $request->no_of_existing_benificiary;
            $allotment->starting_period                       = $request->starting_period;
            $allotment->closing_period                        = $request->closing_period;
            $allotment->created_by_id                         = Auth()->user()->id;
            $allotment->status                                = 1;

            if ($request->has('ward_id_city') && $request->ward_id_city != null) {
                $allotment->location_id = $request->ward_id_city;
            }
            if ($request->has('ward_id_dist') && $request->ward_id_dist != null) {
                $allotment->location_id = $request->ward_id_dist;
            }
            if ($request->has('ward_id_union') && $request->ward_id_union != null) {
                $allotment->location_id = $request->ward_id_union;
            }
            if ($request->has('ward_id_pouro') && $request->ward_id_pouro != null) {
                $allotment->location_id = $request->ward_id_pouro;
            }

            //Dist pouro
            if ($request->location_type == 1) {
                $allotment->district_pourashava_id = $request->district_pouro_id;
                $allotment->ward_id = $request->ward_id_dist;
            }

            //City corporation
            if ($request->location_type == 3) {
                $allotment->city_corp_id = $request->city_id;
                $allotment->thana_id = $request->city_thana_id;
                $allotment->ward_id = $request->ward_id_city;
            }
            //Upazila
            if ($request->location_type == 2) {
                $allotment->upazila_id = $request->thana_id;
                //union
                if ($request->sub_location_type == 2) {
                    $allotment->union_id = $request->union_id;
                    $allotment->ward_id = $request->ward_id_union;
                } else {
                    //pouro
                    $allotment->pourashava_id = $request->pouro_id;
                    $allotment->ward_id = $request->ward_id_pouro;
                }
            }
            $allotment->save();

            return $allotment;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function destroy($id)
    {
        try {
            $allotment = EmergencyAllotment::findOrFail($id);
            $allotment->delete();
            return $allotment;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
