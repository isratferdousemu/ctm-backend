<?php

namespace App\Http\Traits;

trait UserTrait
{
    //approve/pending
    private $userAccountPending = 0;
    private $userAccountApproved = 1;
    private $userAccountBanned = 2;
    private $userAccountRejected = 3;
    private $userAccountDeactivate = 4;
    //user online status
    private $userOnline = 1;
    private $userOffline = 0;

    // user department type
    private $EmployeeDepType = 1;
    private $RiderDepType = 2;

    // user types
    private $superAdminUserType = 1;
    private $adminUserType = 2;
    private $MerchantUserType = 3;
    private $BranchAdminUserType = 4;
    private $DelivaryManUserType= 5;
    private $PickupManUserType= 6;
    private $EmployeeUserType= 6;

    // bank account type
    private $bankAccountTypeCurrent = 1;
    private $bankAccountTypeSaving = 2;

    // payment method type
    private $paymentMethodTypeWallet = 1;
    private $paymentMethodTypeBank = 2;

    // wallet type
    private $walletTypeBkash = 1;
    private $walletTypeRocket = 2;
    private $walletTypeNagad = 3;



    // Main Branch Or Default BranchId
    private $MainBranchId= 0;

    // user prefix
    private $EmpPrefix= 'EMP-';
    private $MerPrefix= 'MER-';

 //email verification code prefix
 protected $employeeEmailVerificationPrefix = 'employee_email_';
 protected $employeeEmailVerificationOtpPrefix = 'employee_email_otp';
 protected $employeeEmailVerificationDirectPrefix = 'employee_email_own_verify_';

 protected $merchantEmailVerificationPrefix = 'merchant_email_';
 protected $merchantEmailVerificationOtpPrefix = 'merchant_email_otp';
 protected $merchantEmailVerificationDirectPrefix = 'merchant_email_own_verify_';
 protected $merchantPhoneOtpPrefix = 'merchant_phone_';

 protected $riderEmailVerificationPrefix = 'rider_email_';
 protected $riderEmailVerificationOtpPrefix = 'rider_email_otp';
 protected $riderEmailVerificationDirectPrefix = 'rider_email_own_verify_';

 protected $adminEmailVerificationPrefix = 'admin_email_';
 protected $adminEmailVerificationOtpPrefix = 'admin_email_otp';
 protected $adminEmailVerificationDirectPrefix = 'admin_email_own_verify_';
 //forget password prefix
 protected $userForgetPasswordPrefix = 'user_forget_password_';
}
