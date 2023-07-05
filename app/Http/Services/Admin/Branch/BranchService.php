<?php

namespace App\Http\Services\Admin\Branch;

use App\Exceptions\BranchErrorMessageWithTextCodeException;
use App\Http\Traits\BranchTrait;
use App\Http\Traits\MessageTrait;
use App\Http\Traits\RoleTrait;
use App\Http\Traits\UserTrait;
use App\Jobs\EmployeeToBranchAdminAssignJob;
use App\Models\Branch;
use App\Models\BranchZone;
use App\Models\User;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class BranchService
{
    use UserTrait, MessageTrait, RoleTrait, BranchTrait, DispatchesJobs;
    public function createBranch(Request $request){
        DB::beginTransaction();
        $GlobalSettings = "";
        try {
            $branch                       = new Branch;
            $branch->branch_name                = $request->branch_name;
            $branch->address                = $request->address;
            $branch->open_hour                = $request->open_hour;
            $branch->branch_phone                = $request->branch_phone;
            $branch->branch_admin_id                = $request->branch_admin_id;
            $branch->save();

            // store branch Zone's
            $zonesIds = $request->zones;
             $zonesIds;
            $this->StoreBranchZones($zonesIds, $branch->id);


            if ($request->filled('branch_admin_id')) {

                // employee userType to Make BranchAdminUserType
                $employee=User::whereId($request->branch_admin_id)->whereUserType($this->EmployeeUserType)->first();
                $employee->branch_id = $branch->id;
                $employee->user_type = $this->BranchAdminUserType;
                $employee->save();

                $branch->status                = $this->BranchActiveStatus;
                $branch->save();

                // assign branch admin role
                $role=Role::where('name', $this->branchAdmin)->first();
                $employee->assignRole([$role->id]);
                $this->dispatch(new EmployeeToBranchAdminAssignJob($employee->email,$employee->full_name,$branch->branch_name,$GlobalSettings));

            }
            DB::commit();
            return $branch;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function StoreBranchZones($zoneIds,$branchId){
        if(Count($zoneIds) >0){
            foreach ($zoneIds as $zoneId) {
                if($zoneId!=null){
                    $branchZone = new BranchZone;
                    $branchZone->zone_id = $zoneId;
                    $branchZone->branch_id = $branchId;
                    $branchZone->save();
                }
                }


        }

    }

    public function UpdateBranch(Request $request, Branch $branch){
        DB::beginTransaction();
        $GlobalSettings = "";
        try {
            $branch->branch_name                = $request->branch_name;
            $branch->address                = $request->address;
            $branch->open_hour                = $request->open_hour;
            $branch->branch_phone                = $request->branch_phone;
            $branch->save();

            // store branch Zone's
            $zonesIds = $request->zones;
             $zonesIds;
             if ($request->filled('zones')) {
                $this->clearOldBranchZones($branch);
                $this->StoreBranchZones($zonesIds, $branch->id);
            }

 if ($request->filled('branch_admin_id') && $branch->branch_admin_id!=$request->branch_admin_id) {
            $branch->branch_admin_id    = $request->branch_admin_id;
            $branch->save();

             $employee=User::whereId($request->branch_admin_id)->whereUserType($this->EmployeeUserType)->first();
            $employee->branch_id = $branch->id;
            $employee->user_type = $this->BranchAdminUserType;
            $employee->save();

            $branch->status                = $this->BranchActiveStatus;
            $branch->save();

            // assign branch admin role
            $role=Role::where('name', $this->branchAdmin)->first();
            $employee->assignRole([$role->id]);
            $this->dispatch(new EmployeeToBranchAdminAssignJob($employee->email,$employee->full_name,$branch->branch_name,$GlobalSettings));

            }
            DB::commit();
            return $branch;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function clearOldBranchZones(Branch $branch)
    {
        BranchZone::whereBranchId($branch->id)->forceDelete();
    }
    public function sendNonAllowedAdminResponse()
    {
        throw new BranchErrorMessageWithTextCodeException(
            422,
            $this->employeeAlreadyBranchAdminTextErrorCode,
            $this->employeeAlreadyBranchAdminMessage
        );
    }
    public function EmployeeIsBranchAdmin($id){
        try {
            return User::whereId($id)->whereUserType($this->BranchAdminUserType)->exists() ? true : false;

        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
