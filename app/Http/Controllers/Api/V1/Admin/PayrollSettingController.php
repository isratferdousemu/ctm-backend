<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\AllowanceProgram;
use App\Models\Installment;
use Illuminate\Http\Request;

class PayrollSettingController extends Controller
{
    public function getAllAllowance(){
        return AllowanceProgram::where("is_active",1)->get();
    }

    public function getAllInstallments(){
        return Installment::where("status",1)->get();
    }
}
