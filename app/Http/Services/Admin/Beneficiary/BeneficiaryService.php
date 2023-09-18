<?php

namespace App\Http\Services\Admin\Beneficiary;


use Carbon\Carbon;


use App\Models\Committee;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\FinancialYear;

use App\Models\CommitteeMember;
use App\Models\AllowanceProgram;
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

            $committee                         = new Committee ;
            $committee->code                   = $request->code;
            $committee->name                   = $request->name;
            $committee->details                = $request->details;
            $committee->program_id             = $request->program_id;
            $committee->division_id            = $request->division_id;
            $committee->district_id            = $request->district_id;
            $committee->office_id              = $request->office_id;
            $committee->location_id            = $request->location_id;
            $committee ->save();

            $input = $request->members;

            foreach($input as $item) {

                 $member                      = new CommitteeMember;
                 $member->committee_id        = $committee->id;
                 $member->member_name    	 = $item['member_name'];
                 $member->designation    	 = $item['designation'];
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

    public function updateCommitee(Request $request){

        DB::beginTransaction();
        try {
            $committee                         = Committee::find($request->id);
            $committee->code                   = $request->code;
            $committee->name                   = $request->name;
            $committee->details                = $request->details;
            $committee->program_id             = $request->program_id;
            $committee->division_id            = $request->division_id;
            $committee->district_id            = $request->district_id;
            $committee->office_id              = $request->office_id;
            $committee->location_id            = $request->location_id;
            $committee ->save();

            CommitteeMember::whereCommittee_id($request->id)->delete();


            $input = $request->members;

            foreach($input as $item) {

                 $member                     =  new CommitteeMember;
                 $member->committee_id        = $committee->id;
                 $member->member_name    	 = $item['member_name'];
                 $member->designation    	 = $item['designation'];
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
