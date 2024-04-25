<?php

namespace App\Http\Services\Admin\GrievanceManagement;

use App\Exceptions\AuthBasicErrorException;
use App\Http\Traits\ApplicationTrait;
use App\Models\ApplicationAllowanceValues;
use App\Models\ApplicationPovertyValues;
use App\Models\FinancialYear;
use App\Models\Grievance;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Log;

class GrievanceService
{
    use ApplicationTrait;
    public function onlineApplicationVerifyCard(Request $request)
    {
        $fakeNID = '12345678';
        $fakeDOB = '87654321';
        if ($request->verification_type == $this->verificationTypeNID) {
            if ($request->verification_number != $fakeNID) {
                // throw new \Exception('NID is not valid');
                // throw new \Exception('NID is not valid');
                throw new AuthBasicErrorException(
                    HttpResponse::HTTP_UNPROCESSABLE_ENTITY,
                    'not_valid',
                    'NID is not valid',
                );
            } else {
                return 'NID is valid';
            }
        }
        if ($request->verification_type == $this->verificationTypeDOB) {
            if ($request->verification_number != $fakeDOB) {
                // throw new \Exception('NID is not valid');
                throw new AuthBasicErrorException(
                    HttpResponse::HTTP_UNPROCESSABLE_ENTITY,
                    'not_valid',
                    'DOB is not valid',
                );
            } else {
                return 'DOB is valid';

            }
        }

    }

    public function onlineApplicationVerifyCardDIS(Request $request)
    {
        $fakeDIS = '12345678';

        if ($request->dis_no != $fakeDIS) {
            throw new AuthBasicErrorException(
                HttpResponse::HTTP_UNPROCESSABLE_ENTITY,
                'not_valid',
                'DIS is not valid',
            );
        } else {
            return 'DIS is valid';
        }
    }

    public function onlineGrievanceEntry(Request $request)
    {
        // return $request->all();
        DB::beginTransaction();

        try {
            $grievance = new Grievance;
            // $application->permanent_mobile = Str::random(10);
            $uniqueaGrievance_id = Str::random(10);

// Check if the generated string already exists in the database
            while (Grievance::where('tracking_no', $uniqueaGrievance_id)->exists()) {
                // If it exists, regenerate the random string
                $uniqueaGrievance_id = Str::random(10);
            }

// Assign the unique random string to the permanent_mobile attribute
            $grievance->is_existing_beneficiary = $request->is_existing_beneficiary;
            $grievance->verification_number = $request->verification_number;
            $grievance->tracking_no = $uniqueaGrievance_id;
            $grievance->date_of_birth = $request->date_of_birth;
            $grievance->verification_type = $request->verification_type;

            $grievance->program_id = $request->program_id;

            $grievance->name = $request->name;
            $grievance->gender_id = $request->gender_id;
            $grievance->email = $request->email;
            $grievance->mobile = $request->mobile;
            $grievance->grievance_type_id = $request->grievance_type_id;
            $grievance->grievance_subject_id = $request->grievance_subject_id;
            $grievance->details = $request->details;

            $grievance->division_id = $request->division_id;
            $grievance->district_id = $request->district_id;
            $grievance->location_type = $request->location_type;
            $grievance->thana_id = $request->thana_id;
            $grievance->sub_location_type = $request->sub_location_type;
            $grievance->union_id = $request->union_id;
            $grievance->pouro_id = $request->pouro_id;
            $grievance->city_id = $request->city_id;
            $grievance->city_thana_id = $request->city_thana_id;
            $grievance->district_pouro_id = $request->district_pouro_id;
            $grievance->ward_id_city = $request->ward_id_city;
            $grievance->ward_id_union = $request->ward_id_union;
            $grievance->ward_id_pouro = $request->ward_id_pouro;
            $grievance->ward_id_dist = $request->ward_id_dist;
            $grievance->post_code = $request->post_code;
            $grievance->address = $request->address;

            // $application->documents = $request->file('documents')->store('public');
            if($request->file('documents')){
                $filePath = $request->file('documents')->store('public');
                $grievance->documents = $filePath;

            }
          

            // $application->signature = $request->file('signature')->store('public');

            $grievance->save();
            DB::commit();

            return $grievance;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

    }
    public function onlineApplicationEdit(Request $request)
    {
        DB::beginTransaction();

        try {
            $application = Application::find($request->id);
            $application->application_id = $request->application_id;
            $application->program_id = $request->program_id;
            $application->verification_type = $request->verification_type;
            $application->verification_number = $request->verification_number;
            $application->age = $request->age;
            $application->date_of_birth = $request->date_of_birth;
            $application->name_en = $request->name_en;
            $application->name_bn = $request->name_bn;
            $application->mother_name_en = $request->mother_name_en;
            $application->mother_name_bn = $request->mother_name_bn;
            $application->father_name_en = $request->father_name_en;
            $application->father_name_bn = $request->father_name_bn;
            $application->spouse_name_en = $request->spouse_name_en;
            $application->spouse_name_bn = $request->spouse_name_bn;
            $application->identification_mark = $request->identification_mark;

            $application->nationality = $request->nationality;
            $application->gender_id = $request->gender_id;
            $application->education_status = $request->education_status;
            $application->profession = $request->profession;
            $application->religion = $request->religion;
            $application->account_type = $request->account_type;
            $application->bank_name = $request->bank_name;
            $application->branch_name = $request->branch_name;
            //  $application->current_location_id =null;

            if ($request->has('ward_id_city') && $request->ward_id_city != null) {
                $application->current_location_id = $request->ward_id_city;
            }
            if ($request->has('ward_id_dist') && $request->ward_id_dist != null) {
                $application->current_location_id = $request->ward_id_dist;
            }
            if ($request->has('ward_id_union') && $request->ward_id_union != null) {
                $application->current_location_id = $request->ward_id_union;
            }
            if ($request->has('ward_id_pouro') && $request->ward_id_pouro != null) {
                $application->current_location_id = $request->ward_id_pouro;
            }
            $application->current_post_code = $request->post_code;
            $application->current_address = $request->address;
            $application->mobile = $request->mobile;
            // $application->current_location_id =null;
            if ($request->has('permanent_ward_id_city') && $request->permanent_ward_id_city !== null) {
                $application->permanent_location_id = $request->permanent_ward_id_city;
            }
            if ($request->has('permanent_ward_id_dist') && ($request->permanent_ward_id_dist !== null)) {
                $application->permanent_location_id = $request->permanent_ward_id_dist;
            }
            if ($request->has('permanent_ward_id_union') && ($request->permanent_ward_id_union !== null)) {
                $application->permanent_location_id = $request->permanent_ward_id_union;
            }
            if ($request->has('permanent_ward_id_pouro') && ($request->permanent_ward_id_pouro !== null)) {
                $application->permanent_location_id = $request->permanent_ward_id_pouro;
            }

            $application->current_location_type_id = $request->location_type;
            $application->current_division_id = $request->division_id;
            $application->current_district_id = $request->district_id;

            //Dist pouro
            if ($request->location_type == 1) {
                $application->current_city_corp_id = null;
                $application->current_thana_id = null;
                $application->current_union_id = null;
                $application->current_pourashava_id = null;
                $application->current_upazila_id = null;
                $application->current_district_pourashava_id = $request->district_pouro_id;
                $application->current_ward_id = $request->ward_id_dist;

            }

            //City corporation
            if ($request->location_type == 3) {
                $application->current_thana_id = null;
                $application->current_union_id = null;
                $application->current_pourashava_id = null;
                $application->current_upazila_id = null;
                $application->current_district_pourashava_id = null;
                $application->current_city_corp_id = $request->city_id;
                $application->current_thana_id = $request->city_thana_id;
                $application->current_ward_id = $request->ward_id_city;

            }

            //Upazila
            if ($request->location_type == 2) {
                $application->current_city_corp_id = null;
                $application->current_thana_id = null;
                $application->current_district_pourashava_id = null;
                $application->current_upazila_id = $request->thana_id;

                //union
                if ($request->sub_location_type == 2) {
                    $application->current_union_id = $request->union_id;
                    $application->current_ward_id = $request->ward_id_union;
                } else {
                    //pouro
                    $application->current_pourashava_id = $request->pouro_id;
                    $application->current_ward_id = $request->ward_id_pouro;
                }

            }

            $application->permanent_location_type_id = $request->permanent_location_type;
            $application->permanent_division_id = $request->permanent_division_id;
            $application->permanent_district_id = $request->permanent_district_id;

            //Dist pouro
            if ($request->permanent_location_type == 1) {
                $application->permanent_city_corp_id = null;
                $application->permanent_thana_id = null;
                $application->permanent_upazila_id = null;
                $application->permanent_union_id = null;
                $application->permanent_pourashava_id = null;
                $application->permanent_district_pourashava_id = $request->permanent_district_pouro_id;
                $application->permanent_ward_id = $request->permanent_ward_id_dist;

            }

            //City corporation
            if ($request->permanent_location_type == 3) {
                $application->permanent_upazila_id = null;
                $application->permanent_union_id = null;
                $application->permanent_pourashava_id = null;
                $application->permanent_district_pourashava_id = null;
                $application->permanent_city_corp_id = $request->permanent_city_id;
                $application->permanent_thana_id = $request->permanent_city_thana_id;
                $application->permanent_ward_id = $request->permanent_ward_id_city;

            }

            //Upazila
            if ($request->permanent_location_type == 2) {
                $application->permanent_city_corp_id = null;
                $application->permanent_thana_id = null;
                $application->permanent_district_pourashava_id = null;
                $application->permanent_upazila_id = $request->permanent_thana_id;
                //union
                if ($request->permanent_sub_location_type == 2) {
                    $application->permanent_union_id = $request->permanent_union_id;
                    $application->permanent_ward_id = $request->permanent_ward_id_union;
                } else {
                    //pouro
                    $application->permanent_pourashava_id = $request->permanent_pouro_id;
                    $application->permanent_ward_id = $request->permanent_ward_id_pouro;
                }

            }

            $application->permanent_post_code = $request->permanent_post_code;
            $application->permanent_address = $request->permanent_address;
            $application->permanent_mobile = $request->permanent_mobile;
            $application->nominee_en = $request->nominee_en;
            $application->nominee_bn = $request->nominee_bn;
            $application->nominee_verification_number = $request->nominee_verification_number;
            $application->nominee_address = $request->nominee_address;
            $application->nominee_date_of_birth = $request->nominee_date_of_birth;

            $application->nominee_relation_with_beneficiary = $request->nominee_relation_with_beneficiary;
            $application->nominee_nationality = $request->nominee_nationality;
            $application->account_name = $request->account_name;
            $application->account_number = $request->account_number;
            $application->account_owner = $request->account_owner;
            $application->marital_status = $request->marital_status;
            $application->email = $request->email;
            $district1 = Application::permanentDistrict($application->permanent_location_id);

            $division = Application::permanentDivision($application->permanent_location_id);
            // $division=$division->id  ;
            $division_cut_off = DB::select("
            SELECT poverty_score_cut_offs.*, financial_years.financial_year AS financial_year, financial_years.end_date
            FROM poverty_score_cut_offs
            JOIN financial_years ON financial_years.id = poverty_score_cut_offs.financial_year_id
            WHERE poverty_score_cut_offs.location_id = ? AND poverty_score_cut_offs.type = 1
            ORDER BY financial_years.end_date DESC LIMIT 1", [$division->id]);
            $division_cut_off = $division_cut_off[0]->id;
            $application->cut_off_id = $division_cut_off;
            $financial_year_id = FinancialYear::Where('status', 1)->first();

            if ($financial_year_id) {
                $financial_year_id = $financial_year_id->id;
                $application->financial_year_id = $financial_year_id;
            }

            if ($request->hasFile('image')) {

                $imagePath = $request->file('image')->store('public');
                $application->image = $imagePath;
            }
            if ($request->hasFile('signature')) {

                $signaturePath = $request->file('signature')->store('public');
                $application->signature = $signaturePath;
            }
            if ($request->hasFile('nominee_image')) {

                $nominee_imagePath = $request->file('nominee_image')->store('public');
                $application->nominee_image = $nominee_imagePath;
            }
            if ($request->hasFile('nominee_signature')) {

                $nominee_signaturePath = $request->file('nominee_signature')->store('public');
                $application->nominee_signature = $nominee_signaturePath;
            }

            $application->save();

            if ($application) {
                $allowance_value = ApplicationAllowanceValues::where('application_id', $application->id)->delete();

                $this->insertApplicationAllowanceValues($request, $application->id);
                $poverty_value = ApplicationPovertyValues::where('application_id', $application->id)->delete();

                $this->insertApplicationPMTValues(json_decode($request->application_pmt), $application->id);

            }

            DB::commit();
            $this->applicationPMTValuesTotal($application->id);
            return $application;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

    }

    public function insertApplicationPMTValues($application_pmt, $application_id)
    {
        DB::beginTransaction();

        try {
            foreach ($application_pmt as $key => $value) {

                // check sub_variables is array or not
                if (is_array($value->sub_variables)) {
                    // insert multiple values
                    foreach ($value->sub_variables as $sub_variable) {
                        $sub_variables = new ApplicationPovertyValues;
                        $sub_variables->application_id = $application_id;
                        $sub_variables->variable_id = $value->variable_id;
                        $sub_variables->sub_variable_id = $sub_variable != 0 ? $sub_variable : null;
                        $sub_variables->save();
                    }
                } else {
                    $ApplicationPovertyValues = new ApplicationPovertyValues;
                    $ApplicationPovertyValues->application_id = $application_id;
                    $ApplicationPovertyValues->variable_id = $value->variable_id;
                    $ApplicationPovertyValues->sub_variable_id = $value->sub_variables != 0 ? $value->sub_variables : null;
                    $ApplicationPovertyValues->save();
                }
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function insertApplicationAllowanceValues($req, $application_id)
    {
        DB::beginTransaction();
        try {
            foreach (json_decode($req->application_allowance_values) as $value) {
                $field_value = new ApplicationAllowanceValues;
                $field_value->application_id = $application_id;
                $field_value->allow_addi_fields_id = $value->allowance_program_additional_fields_id;
                // if(is_array($value->allowance_program_additional_field_values_id)){

                // foreach ($value->allowance_program_additional_field_values_id as $key => $add_field_value) {
                //     $addFieldValue = new ApplicationAllowanceValues;
                //     $addFieldValue->application_id = $application_id;
                //     $addFieldValue->allow_addi_fields_id = $value->allowance_program_additional_fields_id;
                //     $addFieldValue->allow_addi_field_values_id = $add_field_value;
                //     $addFieldValue->value = NULL;
                //     $addFieldValue->save();
                // }
                // }else{
                //     $field_value->allow_addi_field_values_id = $value->allowance_program_additional_field_values_id=='null'?null:$value->allowance_program_additional_field_values_id;
                // }
                // check  $value->value type
                if (isset($value->allowance_program_additional_field_values_id)) {
                    if (is_array($value->allowance_program_additional_field_values_id)) {
                        foreach ($value->allowance_program_additional_field_values_id as $key => $add_field_value) {
                            $addFieldValue = new ApplicationAllowanceValues;
                            $addFieldValue->application_id = $application_id;
                            $addFieldValue->allow_addi_fields_id = $value->allowance_program_additional_fields_id;
                            $addFieldValue->allow_addi_field_values_id = $add_field_value;
                            $addFieldValue->value = null;
                            $addFieldValue->save();
                        }
                    } else {
                        // Handle the case where it's not an array
                        $field_value->allow_addi_field_values_id = $value->allowance_program_additional_field_values_id == 'null' ? null : $value->allowance_program_additional_field_values_id;
                    }
                }

                if (gettype($value->value) == 'object') {
                    $field_value->value = $this->uploadBaseFile($value->file_value, 'application');

                } else {
                    $field_value->value = is_array($value->value) ? null : $value->value;
                }
                $field_value->save();
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function uploadBaseFile($base64File, $folder)
    {

        // base64 to image convert
        $image_parts = explode(";base64,", $base64File);
        // $image_type_aux = explode("image/", $image_parts[0]);
        // $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file = uniqid() . '.png';
        file_put_contents(public_path('uploads/' . $folder . '/' . $file), $image_base64);
        return $file;

    }
    public function uploadFile($file, $folder)
    {
        $file_name = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/' . $folder), $file_name);
        return $file_name;
    }

    // application PMTValues total calculation
    public function applicationPMTValuesTotal($application_id, $per_room_score, $no_of_people_score)
    {
        // $applicationPMTValues = ApplicationPovertyValues::where('application_id', $application_id)->get();
        // $total = 0;

        // foreach ($applicationPMTValues as $key => $value) {
        //     if($value->sub_variable_id!=null){
        //     Log::info('total sub-variable: '.$value->sub_variable->score);

        // }else{
        //     Log::info('total variable: '.$value->variable->score);
        // }
        //     $total += $value->sub_variable_id!=null?$value->sub_variable->score:$value->variable->score;
        // }
        $total = ApplicationPovertyValues::join('variables', 'application_poverty_values.sub_variable_id', '=', 'variables.id')
            ->where('application_id', $application_id)
            ->sum('variables.score');
        Log::info('total score: ' . $total);

        // Poverty score = [(Constant + Sum of the coefficients of all applicable variables + District FE)*100]
        $constant = $this->povertyConstant;
        $districtFE = 0;
        // districtFE get by application permanent_location_id district
        $districtFE = $this->getDistrictFE($application_id);
        Log::info('DistrictfE: ' . $districtFE);
        // $povertyScore = ($constant + $total + $districtFE)*100;
        // $povertyScore = ($constant + $total+ $per_room_score+$no_of_people_score+ $districtFE)*100;
        // $povertyScore = ($constant + $total + $districtFE)*100;
        $povertyScore = ($constant + $total + $per_room_score + $districtFE) * 100;
        Log::info('Poverty: ' . $povertyScore);
        $application = Application::find($application_id);
        $application->score = $povertyScore;
        // $application->identification_mark = $povertyScore+$per_room_score;
        // $application->email = $povertyScore+$no_of_people_score;

        $application->save();
    }

    public function getDistrictFE($application_id)
    {
        $application = Application::find($application_id);
        $districtFE = 0;
        $district = Application::permanentDistrict($application->permanent_location_id);

        // $districtFE =PMTScore::join('financial_years', 'financial_years.id', '=', 'poverty_score_cut_offs.financial_year_id')
        //     ->where('poverty_score_cut_offs.location_id', '=', $district->id)
        //     ->where('poverty_score_cut_offs.default', '=', 1)
        //     ->orderBy('financial_years.end_date', 'desc')
        //     ->select('poverty_score_cut_offs.*')
        //     ->first();

        // $districtFE = PMTScore::where('location_id', $district->id)->where('default',1)->first();
        $districtFE = DB::select("
        SELECT poverty_score_cut_offs.*, financial_years.financial_year AS financial_year, financial_years.end_date
        FROM poverty_score_cut_offs
        JOIN financial_years ON financial_years.id = poverty_score_cut_offs.financial_year_id
        WHERE poverty_score_cut_offs.location_id = ? AND poverty_score_cut_offs.type = 2
        ORDER BY financial_years.end_date DESC LIMIT 1", [$district->id]);
        // $division_cut_off=$division_cut_off[0]->id;
        $districtFE = $districtFE[0];
        // Log::info('districtFE'.$districtFE);
        Log::info('districtFE ' . json_encode($districtFE));
        $districtFE = $districtFE->score;
        return $districtFE;
    }

}
