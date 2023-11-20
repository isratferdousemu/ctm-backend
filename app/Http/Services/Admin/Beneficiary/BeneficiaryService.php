<?php

namespace App\Http\Services\Admin\Beneficiary;


use Carbon\Carbon;


use App\Models\Committee;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\FinancialYear;

use App\Models\CommitteeMember;
use App\Models\AllowanceProgram;
use App\Models\Location;
use App\Models\Lookup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BeneficiaryService
{


    /* -------------------------------------------------------------------------- */
    /*                            Committee Service                              */
    /* -------------------------------------------------------------------------- */

    public function createCommittee(Request $request){

        DB::beginTransaction();
        try {

            $committee                         = new Committee;
            $committee->code                   = mt_rand(100000, 999999);
            $committee->details                = $request->details;
            $committee->committee_type             = $request->committee_type;
            $committee->program_id              = $request->program_id;
            if ($request->has('committee_type')) {
                if ($request->committee_type != 18 || $request->committee_type != 19) {
                    if ($request->committee_type == 12 && $request->has('union_id')) {
                        $committee->location_id = $request->union_id;
                    }
                    else if ($request->committee_type == 13 && $request->has('ward_id')) {
                        $committee->location_id = $request->ward_id;
                    }
                    else if ($request->committee_type == 14 && $request->has('upazila_id')) {
                        $committee->location_id = $request->upazila_id;
                    }
                    else if ($request->committee_type == 15 && $request->has('city_corpo_id')) {
                        $committee->location_id = $request->city_corpo_id;
                    }
                    else if ($request->committee_type == 16 && $request->has('paurashava_id')) {
                        $committee->location_id = $request->paurashava_id;
                    }
                    else if ($request->committee_type == 17 && $request->has('district_id')) {
                        $committee->location_id = $request->district_id;
                    }
                }
                if ($request->committee_type == 18 || $request->committee_type == 19) {
                    $committee->location_id = '-1'; // -1 Stands of Over Bangladesh
                }

            }
            $committee->name = $this->committeeName($request->committee_type, $request->program_id, $committee->location_id);


            $committee ->save();

            $input = $request->members; 

            foreach($input as $item) {

                 $member                      = new CommitteeMember;
                 $member->committee_id        = $committee->id;
                 $member->member_name    	 = $item['member_name'];
                 $member->designation_id    	 = $item['designation_id'];
                 $member->email    	         = $item['email'];
                 $member->address    	     = $item['address'];
                 $member->phone              = $item['phone'];
                 $member->save();

             }

            DB::commit();
            return $committee;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

    }

    public function committeeName($committee_type, $program_id, $location_id)
    {
        $program = AllowanceProgram::find($program_id);
        $committee_type = Lookup::find($committee_type);
        if ($location_id == '-1') {
            $location = 'Bangladesh';
            $name = Str::slug($committee_type->value_en,'_')  . '_' . Str::slug($location,'_') . '_' . Str::slug($program->name_en,'_');
        } else {
            $location = Location::find($location_id);
            $name = Str::slug($committee_type->value_en,'_') . '_' . Str::slug($location->name_en,'_') . '_' . Str::slug($program->name_en,'_');
        }
        return $name;

    }

    public function updateCommitee(Request $request){

        DB::beginTransaction();
        try {
            $committee                         = Committee::find($request->id);
            $committee->code                   = $request->code;
            $committee->details                = $request->details;
            $committee->committee_type             = $request->committee_type;
            $committee->program_id              = $request->program_id;
            if ($request->has('committee_type')) {
                if ($request->committee_type != 18 || $request->committee_type != 19) {
                    if ($request->committee_type == 12 && $request->has('union_id')) {
                        $committee->location_id = $request->union_id;
                    }
                    else if ($request->committee_type == 13 && $request->has('ward_id')) {
                        $committee->location_id = $request->ward_id;
                    }
                    else if ($request->committee_type == 14 && $request->has('upazila_id')) {
                        $committee->location_id = $request->upazila_id;
                    }
                    else if ($request->committee_type == 15 && $request->has('city_corpo_id')) {
                        $committee->location_id = $request->city_corpo_id;
                    }
                    else if ($request->committee_type == 16 && $request->has('paurashava_id')) {
                        $committee->location_id = $request->paurashava_id;
                    }
                    else if ($request->committee_type == 17 && $request->has('district_id')) {
                        $committee->location_id = $request->district_id;
                    }
                }
            }
            $committee->name = $this->committeeName($request->committee_type, $request->program_id, $committee->location_id);

            $committee ->save();

            CommitteeMember::whereCommitteeId($request->id)->delete();


            $input = $request->members;
            // store committee members
            // $committee->members()->sync($input);
            foreach($input as $item) {

                 $member                     =  new CommitteeMember;
                 $member->committee_id        = $committee->id;
                 $member->member_name    	 = $item['member_name'];
                 $member->designation_id    	 = $item['designation_id'];
                 $member->email    	         = $item['email'];
                 $member->address    	     = $item['address'];
                 $member->phone              = $item['phone'];
                 $member->save();

             }

            DB::commit();
            return $committee;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }






}
