<?php

namespace App\Http\Traits;

trait RoleTrait
{
    //role list
    private $superAdminId = 1;
    private $adminId = 2;
    private $MerchantId = 3;
    private $BranchAdminId = 4;
    private $DelivaryManId = 5;
    private $PickupManId = 6;




    private $superAdmin = 'super-admin';
    private $admin = 'admin';
    private $merchant = 'merchant';
    private $branchAdmin = 'branch-admin';
    private $DelivaryMan = 'delivery-man';
    private $PickupMan = 'pickup-man';
}
