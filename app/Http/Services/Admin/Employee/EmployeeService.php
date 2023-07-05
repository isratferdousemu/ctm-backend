<?php

namespace App\Http\Services\Admin\Employee;

use App\Helpers\Helper;
use App\Http\Traits\BranchTrait;
use App\Http\Traits\RoleTrait;
use App\Http\Traits\UserTrait;
use App\Jobs\EmployeeToBranchAdminAssignJob;
use App\Models\Branch;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Bus\DispatchesJobs;

class EmployeeService
{
    use UserTrait,RoleTrait, BranchTrait,DispatchesJobs;

    public function createEmployee(Request $request,$password){
        DB::beginTransaction();
        try {
            $employee                       = new User;
            $employee->full_name                = $request->full_name;
            $employee->branch_id     = $request->branch_id?$request->branch_id : $this->MainBranchId;
            $employee->department_id            = $request->department_id;
            $employee->email            = $request->email;
            $employee->phone            = $request->phone;
            $employee->date_of_birth            = $request->date_of_birth;
            $employee->join_date            = $request->join_date;
            $employee->permanent_address            = $request->permanent_address;
            $employee->present_address            = $request->present_address;
            $employee->employee_shift_id            = $request->employee_shift_id;
            $employee->gender            = $request->gender;
            $employee->salary            = $request->salary;
            $employee->password            = Hash::make($password);
            $employee->user_type            = $this->EmployeeUserType;
            $employee->status            = $this->userAccountApproved;
            $employee->employee_id            =$this->EmpPrefix . Helper::GenerateFourDigitNumber();
            $employee->save();
            if ($request->filled('branch_id')) {
                $this->AssignEmployeeToBranchAdmin($request->branch_id, $employee);
            }

            DB::commit();
            return $employee;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function AssignEmployeeToBranchAdmin($branchId,$employee){

            // employee userType to Make BranchAdminUserType
            $branch=Branch::whereId($branchId)->whereBranchAdminId(null)->first();
            if($branch){
            $GlobalSettings = "";
                $employee->branch_id = $branchId;
                $employee->user_type = $this->BranchAdminUserType;
                $employee->save();

            $branch->branch_admin_id                = $employee->id;
                $branch->status                = $this->BranchActiveStatus;
                $branch->save();

                // assign branch admin role
                $role=Role::where('name', $this->branchAdmin)->first();
                $employee->assignRole([$role->id]);
                $this->dispatch(new EmployeeToBranchAdminAssignJob($employee->email,$employee->full_name,$branch->branch_name,$GlobalSettings));
            }


    }

    public function updateEmployeeService(Request $request, User $employee){
        DB::beginTransaction();
        try {
            $employee->full_name                = $request->full_name;
            $employee->branch_id     = $request->branch_id;
            $employee->department_id            = $request->department_id;
            $employee->email            = $request->email;
            $employee->phone            = $request->phone;
            $employee->date_of_birth            = $request->date_of_birth;
            $employee->join_date            = $request->join_date;
            $employee->permanent_address            = $request->permanent_address;
            $employee->present_address            = $request->present_address;
            $employee->employee_shift_id            = $request->employee_shift_id;
            $employee->gender            = $request->gender;
            $employee->salary            = $request->salary;
            $employee->save();
            if ($request->filled('branch_id')) {
                $this->AssignEmployeeToBranchAdmin($request->branch_id, $employee);
            }
            DB::commit();
            return $employee;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function deleteEmployee(User $employee)
    {
        DB::beginTransaction();
        try {
            User::whereId($employee->id)->delete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
