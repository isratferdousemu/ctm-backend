<?php

namespace App\Http\Services\Admin\Emergency;

use App\Http\Traits\FileUploadTrait;
use App\Models\Beneficiary;
use App\Models\EmergencyBeneficiary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class EmergencyBeneficiaryService
{
    use FileUploadTrait;
    public function getExistingBeneficaries($request)
    {
        $queryParams = $request->only([
            'division_id', 'district_id', 'location_type', 'thana_id',
            'union_id', 'city_id', 'city_thana_id', 'district_pouro_id',
            'searchBy', 'status', 'program_id', 'perPage'
        ]);

        $perPage = $request->query('perPage', 10);
        $query = Beneficiary::query();

        if (!empty($queryParams['division_id'])) {
            $query->where('current_division_id', $queryParams['division_id']);
        }

        if (!empty($queryParams['district_id'])) {
            $query->where('current_district_id', $queryParams['district_id']);
        }

        if (!empty($queryParams['location_type'])) {
            $query->where('current_location_type_id', $queryParams['location_type']);
        }

        if (!empty($queryParams['thana_id'])) {
            $query->where('current_upazila_id', $queryParams['thana_id']);
        }

        if (!empty($queryParams['union_id'])) {
            $query->where('current_union_id', $queryParams['union_id']);
        }

        if (!empty($queryParams['city_id'])) {
            $query->where('current_city_corp_id', $queryParams['city_id']);
        }

        // if (!empty($queryParams['city_thana_id'])) {
        //     $query->where('current_city_thana_id', $queryParams['city_thana_id']);
        // }

        if (!empty($queryParams['district_pouro_id'])) {
            $query->where('current_district_pourashava_id', $queryParams['district_pouro_id']);
        }

        if (!empty($queryParams['status'])) {
            $query->where('status', $queryParams['status']);
        }

        // Handle searchBy - Assuming 'searchBy' can be used to filter by name or other text fields
        // if (!empty($queryParams['searchBy'])) {
        //     $searchBy = $queryParams['searchBy'];
        //     $query->where(function ($q) use ($searchBy) {
        //         $q->where('name_en', 'LIKE', "%{$searchBy}%")
        //             ->orWhere('name_bn', 'LIKE', "%{$searchBy}%")
        //             ->orWhere('email', 'LIKE', "%{$searchBy}%");
        //         // Add more fields as needed
        //     });
        // }

        $data = $query->with(
            'program',
            'permanentDivision',
            'permanentDistrict',
            'permanentCityCorporation',
            'permanentDistrictPourashava',
            'permanentUpazila',
            'permanentPourashava',
            'permanentThana',
            'permanentUnion',
            'permanentWard'
        )->paginate($perPage);

        return $data;
    }

    public function list(Request $request)
    {
        $program_id = $request->query('program_id');

        $beneficiary_id = $request->query('beneficiary_id');
        $nominee_name = $request->query('nominee_name');
        $account_number = $request->query('account_number');
        $verification_number = $request->query('nid');
        $status = $request->query('status');

        $perPage = $request->query('perPage', 10);
        $sortByColumn = $request->query('sortBy', 'created_at');
        $orderByDirection = $request->query('orderBy', 'asc');

        $query = EmergencyBeneficiary::query();
        if ($program_id)
            $query = $query->where('program_id', $program_id);

        $query = $this->applyLocationFilter($query, $request);

        // advance search
        if ($beneficiary_id)
            $query = $query->where('application_id', $beneficiary_id);
        if ($nominee_name)
            $query = $query->whereRaw('UPPER(nominee_en) LIKE "%' . strtoupper($nominee_name) . '%"');
        if ($account_number)
            $query = $query->where('account_number', $account_number);
        if ($verification_number)
            $query = $query->where('verification_number', $verification_number);
        if ($status)
            $query = $query->where('status', $status);


        return $query->with('emergencyAllotment',
            'permanentDivision',
            'permanentDistrict',
            'permanentCityCorporation',
            'permanentDistrictPourashava',
            'permanentUpazila',
            'permanentPourashava',
            'permanentThana',
            'permanentUnion',
            'permanentWard')->orderBy("$sortByColumn", "$orderByDirection")->paginate($perPage);
    }

    /**
     * @param $query
     * @param $request
     * @return mixed
     */
    private function applyLocationFilter($query, $request): mixed
    {
        $user = auth()->user()->load('assign_location.parent.parent.parent.parent');
        $assignedLocationId = $user->assign_location?->id;
        $subLocationType = $user->assign_location?->location_type;
        // 1=District Pouroshava, 2=Upazila, 3=City Corporation
        $locationType = $user->assign_location?->type;
        // division->district
        // localtion_type=1; district-pouroshava->ward
        // localtion_type=2; thana->{union/pouro}->ward
        // localtion_type=3; thana->ward

        $division_id = $request->query('division_id');
        $district_id = $request->query('district_id');
        $location_type_id = $request->query('location_type');
        $city_corp_id = $request->query('city_corp_id');
        $district_pourashava_id = $request->query('district_pourashava_id');
        $upazila_id = $request->query('upazila_id');
//        $sub_location_type_id = $request->query('sub_location_type_id');
        $pourashava_id = $request->query('pourashava_id');
        $thana_id = $request->query('thana_id');
        $union_id = $request->query('union_id');
        $ward_id = $request->query('ward_id');

        if ($user->assign_location) {
            if ($locationType == 'ward') {
                $ward_id = $assignedLocationId;
                $division_id = $district_id = $city_corp_id = $district_pourashava_id = $upazila_id = $thana_id = $pourashava_id = $union_id = -1;
            } elseif ($locationType == 'union') {
                $union_id = $assignedLocationId;
                $division_id = $district_id = $city_corp_id = $district_pourashava_id = $upazila_id = $thana_id = $pourashava_id = -1;
            } elseif ($locationType == 'pouro') {
                $pourashava_id = $assignedLocationId;
                $division_id = $district_id = $city_corp_id = $district_pourashava_id = $upazila_id = $thana_id = $union_id = -1;
            } elseif ($locationType == 'thana') {
                if ($subLocationType == 2) {
                    $upazila_id = $assignedLocationId;
                    $division_id = $district_id = $city_corp_id = $district_pourashava_id = $thana_id = -1;
                } elseif ($subLocationType == 3) {
                    $thana_id = $assignedLocationId;
                    $division_id = $district_id = $city_corp_id = $district_pourashava_id = $upazila_id = -1;
                } else {
                    $query = $query->where('id', -1); // wrong location type
                }
            } elseif ($locationType == 'city') {
                if ($subLocationType == 1) {
                    $district_pourashava_id = $assignedLocationId;
                    $division_id = $district_id = $city_corp_id = $upazila_id = $thana_id = -1;
                } elseif ($subLocationType == 3) {
                    $city_corp_id = $assignedLocationId;
                    $division_id = $district_id = $district_pourashava_id = $upazila_id = $thana_id = -1;
                } else {
                    $query = $query->where('id', -1); // wrong location type
                }
            } elseif ($locationType == 'district') {
                $district_id = $assignedLocationId;
                $division_id = -1;
            } elseif ($locationType == 'division') {
                $division_id = $assignedLocationId;
            } else {
                $query = $query->where('id', -1); // wrong location assigned
            }
        }

        if ($division_id && $division_id > 0)
            $query = $query->where('permanent_division_id', $division_id);
        if ($district_id && $district_id > 0)
            $query = $query->where('permanent_district_id', $district_id);
        if ($location_type_id && $location_type_id > 0)
            $query = $query->where('permanent_location_type_id', $location_type_id);
        if ($city_corp_id && $city_corp_id > 0)
            $query = $query->where('permanent_city_corp_id', $city_corp_id);
        if ($district_pourashava_id && $district_pourashava_id > 0)
            $query = $query->where('permanent_district_pourashava_id', $district_pourashava_id);
        if ($upazila_id && $upazila_id > 0)
            $query = $query->where('permanent_upazila_id', $upazila_id);
        if ($pourashava_id && $pourashava_id > 0)
            $query = $query->where('permanent_pourashava_id', $pourashava_id);
        if ($thana_id && $thana_id > 0)
            $query = $query->where('permanent_thana_id', $thana_id);
        if ($union_id && $union_id > 0)
            $query = $query->where('permanent_union_id', $union_id);
        if ($ward_id && $ward_id > 0)
            $query = $query->where('permanent_ward_id', $ward_id);

        return $query;
    }

    public function edit($id)
    {
        $beneficiary = EmergencyBeneficiary::findOrFail($id);
        return $beneficiary;
    }


    public function update($request, $id)
    {

        $beneficiary = EmergencyBeneficiary::findOrFail($id);
        try {
            // Handle nominee_image upload
//            if ($request->hasFile('nominee_image')) {
//                // Delete old image if it exists
//                if ($beneficiary->nominee_image) {
//                    Storage::delete('public/' . $beneficiary->nominee_image);
//                }
//                // Store new signature and update path
//                $beneficiary->nominee_image = $request->file('nominee_signature')->store('public');
//            }

//            $fileAudit = $request->file('nominee_image');s
//            $oldFileAudit = $beneficiary->nominee_image ?? null;
//            if ($fileAudit !=  $oldFileAudit) {
//                $newFileAudit = $this->base64Image($fileAudit);
//                if ($newFileAudit) {
//                    $beneficiary->nominee_image = $newFileAudit;
//                    if ($oldFileAudit) {
//                        unlink(storage_path("app/public/" . $oldFileAudit));
//                    }
//                }
//            } else {
//                $beneficiary->nominee_image = $fileAudit;
//            }
//
//
//            $file = $request->file('nominee_signature');
//            $oldFile = $beneficiary->nominee_signature ?? null;
//            if ($file !=  $oldFile) {
//                $newFile = $this->base64Image($file);
//                if ($newFile) {
//                    $beneficiary->nominee_signature = $newFile;
//                    if ($oldFile) {
//                        unlink(storage_path("app/public/" . $oldFile));
//                    }
//                }
//            } else {
//                $beneficiary->nominee_signature = $file;
//            }
//             Handle nominee_signature upload
//            if ($request->hasFile('nominee_signature')) {
//                // Delete old signature if exists
//                if ($beneficiary->nominee_signature) {
//                    Storage::delete($beneficiary->nominee_signature);
//                }
//                // Store new signature and update path
//                $beneficiary->nominee_signature = $request->file('nominee_signature')->store('public');
//            }

            // Update other attributes
            $beneficiary->fill($request->only(['nominee_en', 'nominee_bn', 'nominee_verification_number', 'nominee_address',
                'nominee_relation_with_beneficiary', 'nominee_nationality',
                'nominee_date_of_birth', 'account_name', 'account_number',
                'account_owner', 'account_type', 'bank_name', 'branch_name','email']));
            if ($request->hasFile('nominee_image'))
                $beneficiary->nominee_image = $request->file('nominee_image')->store('public');

            if ($request->hasFile('nominee_signature'))
                $beneficiary->nominee_signature = $request->file('nominee_signature')->store('public');

            // Save the beneficiary object to persist the changes
            $beneficiary->save();
            return $beneficiary;
        } catch (\Throwable $th) {
            throw $th;
        }

    }
    public function base64Image($file)
    {

        $position = strpos($file, ';');
        $sub = substr($file, 0, $position);
        $ext = explode('/', $sub)[1];
        if(isset($ext) && ($ext == "png" || $ext == "jpeg" || $ext == "jpg" || $ext == "pdf")) {
            $newImageName = time() . "." . $ext;
        } else {
            $ext2 = explode('.', $ext)[3];
            if ($ext2 == "document") {
                $ext = "docx";
                $newImageName = time() . "." . $ext;
            } elseif ($ext2 == "sheet") {
                $ext = "xlsx";
                $newImageName = time() . "." . $ext;
            } else {
                $newImageName = "";
            }
            $newImageName = time() . "." . $ext;
        }
        $this->validateFile($file);
        $this->createUploadFolder();
        $uploadedPath = $this->uploadFile($newImageName, $file);
        return $uploadedPath;
    }
    private function uploadFile($newImageName, $file)
    {

        $path = $this->uploadPath . '/' . $this->folderName . '/';
        if (Storage::putFileAs('public/' . $path, $file, $newImageName)) {
            return  $path . $newImageName;
        }
    }
    public function store($request): EmergencyBeneficiary
    {
        try {
            $beneficiary = new EmergencyBeneficiary();
            $beneficiary->allotment_id = $request->emergency_allotment_id;
            $beneficiary->beneficiary_id = \Str::random(4);
            $beneficiary->verification_type = $request->verification_type;
            $beneficiary->verification_number = $request->verification_number;
            $beneficiary->age = $request->age;
            $beneficiary->date_of_birth = $request->date_of_birth;
            $beneficiary->name_en = $request->name_en;
            $beneficiary->name_bn = $request->name_bn;
            $beneficiary->mother_name_en = $request->mother_name_en;
            $beneficiary->mother_name_bn = $request->mother_name_bn;
            $beneficiary->father_name_en = $request->father_name_en;
            $beneficiary->father_name_bn = $request->father_name_bn;
            $beneficiary->spouse_name_en = $request->spouse_name_en;
            $beneficiary->spouse_name_bn = $request->spouse_name_bn;
            $beneficiary->identification_mark = $request->identification_mark;
            $beneficiary->gender_id = $request->gender_id;
            $beneficiary->education_status = $request->education_status;
            $beneficiary->profession = $request->profession;
            $beneficiary->religion = $request->religion;
            $beneficiary->nationality = $request->nationality;
            $beneficiary->account_type = $request->account_type;
            $beneficiary->bank_name = $request->bank_name;
//          $beneficiary->mfs_name = $request->mfs_name;
            $beneficiary->branch_name = $request->branch_name;

            if ($request->has('ward_id_city') && $request->ward_id_city != null) {
                $beneficiary->current_location_id = $request->ward_id_city;
            }
            if ($request->has('ward_id_dist') && $request->ward_id_dist != null) {
                $beneficiary->current_location_id = $request->ward_id_dist;
            }
            if ($request->has('ward_id_union') && $request->ward_id_union != null) {
                $beneficiary->current_location_id = $request->ward_id_union;
            }
            if ($request->has('ward_id_pouro') && $request->ward_id_pouro != null) {
                $beneficiary->current_location_id = $request->ward_id_pouro;
            }
            $beneficiary->current_post_code = $request->post_code;
            $beneficiary->current_address = $request->address;
            $beneficiary->current_mobile = $request->mobile;
            if ($request->has('permanent_ward_id_city') && $request->permanent_ward_id_city !== null) {
                $beneficiary->permanent_location_id = $request->permanent_ward_id_city;
            }
            if ($request->has('permanent_ward_id_dist') && ($request->permanent_ward_id_dist !== null)) {
                $beneficiary->permanent_location_id = $request->permanent_ward_id_dist;
            }
            if ($request->has('permanent_ward_id_union') && ($request->permanent_ward_id_union !== null)) {
                $beneficiary->permanent_location_id = $request->permanent_ward_id_union;
            }
            if ($request->has('permanent_ward_id_pouro') && ($request->permanent_ward_id_pouro !== null)) {
                $beneficiary->permanent_location_id = $request->permanent_ward_id_pouro;
            }
            $beneficiary->current_division_id = $request->division_id;
            $beneficiary->current_district_id = $request->district_id;
            $beneficiary->current_location_type = $request->location_type;

            //Dist pouro
            if ($request->location_type == 1) {
                $beneficiary->current_district_pourashava_id = $request->district_pouro_id;
                $beneficiary->current_ward_id = $request->ward_id_dist;
            }

            //City corporation
            if ($request->location_type == 3) {
                $beneficiary->current_city_corp_id = $request->city_id;
                $beneficiary->current_thana_id = $request->city_thana_id;
                $beneficiary->current_ward_id = $request->ward_id_city;
            }

            //Upazila
            if ($request->location_type == 2) {
                $beneficiary->current_upazila_id = $request->thana_id;
                //union
                if ($request->sub_location_type == 2) {
                    $beneficiary->current_union_id = $request->union_id;
                    $beneficiary->current_ward_id = $request->ward_id_union;
                } else {
                    //pouro
                    $beneficiary->current_pourashava_id = $request->pouro_id;
                    $beneficiary->current_ward_id = $request->ward_id_pouro;
                }


            }

            $beneficiary->permanent_division_id = $request->permanent_division_id;
            $beneficiary->permanent_district_id = $request->permanent_district_id;
            $beneficiary->permanent_location_type = $request->permanent_location_type;

            //Dist pouro
            if ($request->permanent_location_type == 1) {
                $beneficiary->permanent_district_pourashava_id = $request->permanent_district_pouro_id;
                $beneficiary->permanent_ward_id = $request->permanent_ward_id_dist;
            }


            //City corporation
            if ($request->permanent_location_type == 3) {
                $beneficiary->permanent_city_corp_id = $request->permanent_city_id;
                $beneficiary->permanent_thana_id = $request->permanent_city_thana_id;
                $beneficiary->permanent_ward_id = $request->permanent_ward_id_city;
            }

            //Upazila
            if ($request->permanent_location_type == 2) {
                $beneficiary->permanent_upazila_id = $request->permanent_thana_id;
                //union
                if ($request->permanent_sub_location_type == 2) {
                    $beneficiary->permanent_union_id = $request->permanent_union_id;
                    $beneficiary->permanent_ward_id = $request->permanent_ward_id_union;
                } else {
                    //pouro
                    $beneficiary->permanent_pourashava_id = $request->permanent_pouro_id;
                    $beneficiary->permanent_ward_id = $request->permanent_ward_id_pouro;
                }

            }
            $beneficiary->permanent_post_code = $request->permanent_post_code;
            $beneficiary->permanent_address = $request->permanent_address;
            $beneficiary->permanent_mobile = $request->permanent_mobile;
            $beneficiary->nominee_en = $request->nominee_en;
            $beneficiary->nominee_bn = $request->nominee_bn;
            $beneficiary->nominee_verification_number = $request->nominee_verification_number;
            $beneficiary->nominee_address = $request->nominee_address;
            $beneficiary->nominee_date_of_birth = $request->nominee_date_of_birth;
            $beneficiary->nominee_relation_with_beneficiary = $request->nominee_relation_with_beneficiary;
            $beneficiary->nominee_nationality = $request->nominee_nationality;
            $beneficiary->account_name = $request->account_name;
            $beneficiary->account_number = $request->account_number;
            $beneficiary->account_owner = $request->account_owner;
            $beneficiary->marital_status = $request->marital_status;
            $beneficiary->email = $request->email;

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('public');
                $beneficiary->image = $imagePath;
            }
            // Check if signature file is present and store it
            if ($request->hasFile('signature')) {
                $signaturePath = $request->file('signature')->store('public');
                $beneficiary->signature = $signaturePath;
            }

            // Check if nominee image file is present and store it
            if ($request->hasFile('nominee_image')) {
                $nominee_imagePath = $request->file('nominee_image')->store('public');
                $beneficiary->nominee_image = $nominee_imagePath;
            }

            // Check if nominee signature file is present and store it
            if ($request->hasFile('nominee_signature')) {
                $nominee_signaturePath = $request->file('nominee_signature')->store('public');
                $beneficiary->nominee_signature = $nominee_signaturePath;
            }

            $beneficiary->save();
            DB::commit();
            return $beneficiary;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }


    }


    public function destroy($id)
    {
        try {
            $beneficiary = EmergencyBeneficiary::findOrFail($id);
            $beneficiary->delete();
            return $beneficiary;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
