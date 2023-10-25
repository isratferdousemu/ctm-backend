<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\Admin\Application\ApplicationService;
use App\Http\Traits\BeneficiaryTrait;
use App\Http\Traits\MessageTrait;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    use MessageTrait, BeneficiaryTrait;
    private $applicationService;

    public function __construct(ApplicationService $applicationService) {
        $this->applicationService= $applicationService;
    }

    public function getBeneficiaryByLocation(){

        $beneficiaries = $this->getBeneficiary();
        $applications = $this->applications();

    }
}
