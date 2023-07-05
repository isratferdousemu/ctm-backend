<?php

namespace App\Http\Traits;

trait BranchTrait
{
    private $BranchPendingStatus = 0;
    private $BranchActiveStatus = 1;
    private $BranchDeactivateStatus = 2;
}
