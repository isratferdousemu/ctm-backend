<?php

namespace App\Http\Services\Admin\Office;

use App\Http\Traits\OfficeTrait;
use App\Models\Office;
use App\Models\OfficeHasWard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OfficeService
{


    /* -------------------------------------------------------------------------- */
    /*                              Office Service                              */
    /* -------------------------------------------------------------------------- */

    public function createOffice(Request $request)
    {
        $selectedWardsDetails = json_decode($request->input('selectedWardsDetails'), true);


        DB::beginTransaction();
        try {

            $office = new Office;
            if ($request->has('office_type')) {
                $office->office_type = $request->office_type;
                if ($request->office_type != 4 || $request->office_type != 5) {
                    if ($request->office_type == 6) {
                        if ($request->has('division_id')) {
                            $office->assign_location_id = $request->division_id;
                        }
                    } elseif ($request->office_type == 7) {
                        if ($request->has('district_id')) {
                            $office->assign_location_id = $request->district_id;
                        }
                    } elseif ($request->office_type == 8 || $request->office_type == 10 || $request->office_type == 11) {
                        if ($request->has('upazila_id')) {
                            $office->assign_location_id = $request->upazila_id;
                        }
                    } elseif ($request->office_type == 9) {
                        if ($request->has('city_id')) {
                            $office->assign_location_id = $request->city_id;
                        }
                    } elseif ($request->office_type == 35) {
                        if ($request->has('dist_pouro_id')) {
                            $office->assign_location_id = $request->dist_pouro_id;
                        }
                    }
                }
            }
            $office->name_en = $request->name_en;
            $office->name_bn = $request->name_bn;
            $office->office_address = $request->office_address;
            $office->comment = $request->comment;
            $office->status = $request->status;
            $office->save();

            $data = $request->x;


            // if (is_array($data) && count($data) > 0) {
            if ($request->office_type == 9 && $selectedWardsDetails) {
                foreach ($selectedWardsDetails as $wardDetails) {
                    if (is_array($wardDetails) && count($wardDetails)) {
                        // dd($isWardExists);
                        $ward_under_office = new OfficeHasWard;
                        $ward_under_office->office_id = $office->id;
                        $ward_under_office->ward_id = $wardDetails['ward_id'];
                        // You may need to adjust this based on your data structure
                        $ward_under_office->division_id = $wardDetails['division_id'];
                        $ward_under_office->city_id = $wardDetails['city_id'];
                        $ward_under_office->district_id = $wardDetails['district_id'];
                        $ward_under_office->thana_id = $wardDetails['thana_id'];
                        $ward_under_office->save();

                    }
                }
            }


            if ($request->office_type == 10 && $request->selectedWardsDetails_UCDUpazila) {
                $wards = json_decode($request->selectedWardsDetails_UCDUpazila, true);

                foreach ($wards as $ward) {
                    $officeWard = new OfficeHasWard;
                    $officeWard->office_id = $office->id;
                    $officeWard->ward_id = $ward['ward_id'];
                    $officeWard->division_id = $ward['division_id'];
                    $officeWard->district_id = $ward['district_id'];
                    $officeWard->union_id = $ward['union_id'] ?? null;
                    $officeWard->pouro_id = $ward['pouro_id'] ?? null;
                    $officeWard->save();
                }
            }

            DB::commit();
            return $office;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function updateOffice(Request $request)
    {
        $selectedWardsDetails = json_decode($request->input('selectedWardsDetails'), true);
        DB::beginTransaction();
        try {
            $office = Office::find($request->id);
            if ($request->has('office_type')) {
                $office->office_type = $request->office_type;
                if ($request->office_type != 4 || $request->office_type != 5) {
                    if ($request->office_type == 6) {
                        if ($request->has('division_id')) {
                            $office->assign_location_id = $request->division_id;
                        }
                    } elseif ($request->office_type == 7) {
                        if ($request->has('district_id')) {
                            $office->assign_location_id = $request->district_id;
                        }
                    } elseif ($request->office_type == 8 || $request->office_type == 10 || $request->office_type == 11) {
                        if ($request->has('upazila_id')) {
                            $office->assign_location_id = $request->upazila_id;
                        }
                    } elseif ($request->office_type == 9) {
                        if ($request->has('city_id')) {
                            $office->assign_location_id = $request->city_id;
                        }
                    } elseif ($request->office_type == 35) {
                        if ($request->has('dist_pouro_id')) {
                            $office->assign_location_id = $request->dist_pouro_id;
                        }
                    }
                }
            }
            $office->name_en = $request->name_en;
            $office->name_bn = $request->name_bn;
            $office->office_address = $request->office_address;
            $office->comment = $request->comment;
            $office->status = $request->status;
            $office->version = $office->version + 1;
            $office->save();


            $data = $request->selectedWards;


            OfficeHasWard::where('office_id', $request->id)->delete();
            if ($request->office_type == 9 && $selectedWardsDetails) {
                foreach ($selectedWardsDetails as $wardDetails) {
                    if (is_array($wardDetails) && count($wardDetails)) {
                        $ward_under_office = new OfficeHasWard;
                        $ward_under_office->office_id = $office->id;
                        $ward_under_office->ward_id = $wardDetails['ward_id'];
                        // You may need to adjust this based on your data structure
                        $ward_under_office->division_id = $wardDetails['division_id'];
                        $ward_under_office->city_id = $wardDetails['city_id'];
                        $ward_under_office->district_id = $wardDetails['district_id'];
                        $ward_under_office->thana_id = $wardDetails['thana_id'];
                        $ward_under_office->save();
                    }
                }
            }


            if ($request->office_type == 10 && $request->selectedWardsDetails_UCDUpazila) {
                $wards = json_decode($request->selectedWardsDetails_UCDUpazila, true);

                foreach ($wards as $ward) {
                    $officeWard = new OfficeHasWard;
                    $officeWard->office_id = $office->id;
                    $officeWard->ward_id = $ward['ward_id'];
                    $officeWard->division_id = $ward['division_id'];
                    $officeWard->district_id = $ward['district_id'];
                    $officeWard->union_id = $ward['union_id'] ?? null;
                    $officeWard->pouro_id = $ward['pouro_id'] ?? null;
                    $officeWard->save();
                }
            }

            DB::commit();
            return $office;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function getAllOfficeList(Request $request)
    {
        $location_id = $request->query('location_id');
        $office_type = $request->query('office_type');
        $query = Office::query();
        $query->select('id', 'name_en', 'name_bn');
        if ($location_id)
            $query = $query->where('assign_location_id', $location_id);
        if ($office_type)
            $query = $query->where('office_type', $office_type);

        return $query->select('id', 'name_en', 'name_bn')->orderBy("office_type")
            ->orderBy("name_en")
            ->get();
    }


}
