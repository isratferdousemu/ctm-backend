<?php

namespace App\Http\Services\Admin\Application;

use App\Exceptions\AuthBasicErrorException;
use App\Http\Traits\ApplicationTrait;
use App\Models\Application;
use App\Models\ApplicationAllowanceValues;
use App\Models\ApplicationPovertyValues;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Log;
use Response;

class ApplicationService
{
    use ApplicationTrait;
    public function onlineApplicationVerifyCard(Request $request)
    {
        $fakeNID = '12345678';
        $fakeDOB = '87654321';
        if ($request->verification_type == $this->verificationTypeNID) {
            if($request->verification_number != $fakeNID){
            // throw new \Exception('NID is not valid');
            // throw new \Exception('NID is not valid');
             throw new AuthBasicErrorException(
                HttpResponse::HTTP_UNPROCESSABLE_ENTITY,
                'not_valid',
                'NID is not valid',
            );
            }else{
                return 'NID is valid';
            }
        }
        if ($request->verification_type == $this->verificationTypeDOB) {
            if($request->verification_number != $fakeDOB){
                // throw new \Exception('NID is not valid');
                throw new AuthBasicErrorException(
                    HttpResponse::HTTP_UNPROCESSABLE_ENTITY,
                    'not_valid',
                    'DOB is not valid',
                );
                }else{
                    return 'DOB is valid';

                }
        }

    }

    public function onlineApplicationVerifyCardDIS(Request $request)
    {
        $fakeDIS = '12345678';

        if($request->dis_no != $fakeDIS){
            throw new AuthBasicErrorException(
            HttpResponse::HTTP_UNPROCESSABLE_ENTITY,
            'not_valid',
            'DIS is not valid',
        );
        }else{
            return 'DIS is valid';
        }
    }

    public function onlineApplicationRegistration(Request $request){
        DB::beginTransaction();

        try {
            $application = new Application;
            $application->application_id = Str::random(10);
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
            if($request->hasFile('image') && $request->image!=null){
                $application->image = $this->uploadFile($request->image, 'application');
            }
            if($request->hasFile('signature') && $request->signature!=null){
                $application->signature = $this->uploadFile($request->signature, 'application');
            }
            $application->nationality = $request->nationality;
            $application->gender_id = $request->gender_id;
            $application->education_status = $request->education_status;
            $application->profession = $request->profession;
            $application->religion = $request->religion;
            if($request->has('city_thana_id') && $request->city_thana_id!=null){
                $application->current_location_id              = $request->city_thana_id;
            }
            if($request->has('district_pouro_id') && $request->district_pouro_id!=null){
                $application->current_location_id              = $request->district_pouro_id;
            }
            if($request->has('union_id') && $request->union_id!=null){
                $application->current_location_id              = $request->union_id;
            }
            $application->current_post_code = $request->post_code;
            $application->current_address = $request->address;
            $application->mobile = $request->mobile;
            if($request->has('permanent_city_thana_id') && $request->permanent_city_thana_id!=null){
                $application->permanent_location_id              = $request->permanent_city_thana_id;
            }
            if($request->has('permanent_district_pouro_id') && $request->permanent_district_pouro_id!=null){
                $application->permanent_location_id              = $request->permanent_district_pouro_id;
            }
            if($request->has('permanent_union_id') && $request->permanent_union_id!=null){
                $application->permanent_location_id              = $request->permanent_union_id;
            }
            $application->permanent_post_code = $request->permanent_post_code;
            $application->permanent_address = $request->permanent_address;
            $application->permanent_mobile = $request->permanent_mobile;
            $application->nominee_en = $request->nominee_en;
            $application->nominee_bn = $request->nominee_bn;
            $application->nominee_verification_number = $request->nominee_verification_number;
            $application->nominee_address = $request->nominee_address;
            if($request->hasFile('nominee_image') && $request->nominee_image!=null){
                $application->nominee_image = $this->uploadFile($request->nominee_image, 'application');
            }
            if($request->hasFile('nominee_signature') && $request->nominee_signature!=null){
                $application->nominee_signature = $this->uploadFile($request->nominee_signature, 'application');
            }
            $application->nominee_relation_with_beneficiary = $request->nominee_relation_with_beneficiary;
            $application->nominee_nationality = $request->nominee_nationality;
            $application->account_name = $request->account_name;
            $application->account_number = $request->account_number;
            $application->account_owner = $request->account_owner;
            $application->marital_status = $request->marital_status;
            $application->email = $request->email;

            $application->save();

            if($application){
                // insert PMT score values
                $this->insertApplicationPMTValues(json_decode($request->application_pmt), $application->id);
                // insert application allowance values
                $this->insertApplicationAllowanceValues($request, $application->id);
            }

            DB::commit();
            return $application;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

    }

    public function insertApplicationPMTValues($application_pmt, $application_id){
        DB::beginTransaction();

        try {
            foreach ($application_pmt as $key => $value) {

                $ApplicationPovertyValues = new ApplicationPovertyValues;
                $ApplicationPovertyValues->application_id = $application_id;
                $ApplicationPovertyValues->variable_id = $value->variable_id;
                // check sub_variables is array or not
                if (is_array($value->sub_variables)) {
                    // insert multiple values
                    foreach ($value->sub_variables as $sub_variable) {
                        $sub_variables = new ApplicationPovertyValues;
                        $sub_variables->application_id = $application_id;
                        $sub_variables->variable_id = $value->variable_id;
                        $sub_variables->sub_variable_id = $sub_variable!=0?$sub_variable:null;
                        $sub_variables->save();
                    }
                }else{
                    $ApplicationPovertyValues->sub_variable_id = $value->sub_variables!=0?$value->sub_variables:null;
                }
                $ApplicationPovertyValues->save();
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function insertApplicationAllowanceValues($req, $application_id){
        DB::beginTransaction();
        try {
            foreach (json_decode($req->application_allowance_values) as $value) {
            $field_value = New ApplicationAllowanceValues;
            $field_value->application_id = $application_id;
            $field_value->allow_addi_fields_id = $value->allowance_program_additional_fields_id;
            if(is_array($value->allowance_program_additional_field_values_id)){

            foreach ($value->allowance_program_additional_field_values_id as $key => $add_field_value) {
                $addFieldValue = new ApplicationAllowanceValues;
                $addFieldValue->application_id = $application_id;
                $addFieldValue->allow_addi_fields_id = $value->allowance_program_additional_fields_id;
                $addFieldValue->allow_addi_field_values_id = $add_field_value;
                $addFieldValue->value = NULL;
                $addFieldValue->save();
            }
            }else{
                $field_value->allow_addi_field_values_id = $value->allowance_program_additional_field_values_id=='null'?null:$value->allowance_program_additional_field_values_id;
            }
            // check  $value->value type
            if(gettype($value->value)=='object'){
                $field_value->value = $this->uploadBaseFile($value->file_value, 'application');
            }else{
                $field_value->value = is_array($value->value)?NULL:$value->value;
            }
            $field_value->save();
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function uploadBaseFile($base64File, $folder){

    // base64 to image convert
    $image_parts = explode(";base64,", $base64File);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_type = $image_type_aux[1];
    $image_base64 = base64_decode($image_parts[1]);
        $file = uniqid() . '.png';
        file_put_contents(public_path('uploads/' .$folder.'/' . $file), $image_base64);
        return $file;



    }
    public function uploadFile($file, $folder)
    {
        $file_name = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/' . $folder), $file_name);
        return $file_name;
    }

}
