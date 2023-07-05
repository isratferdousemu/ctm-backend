<?php

namespace App\Http\Traits;

trait MessageTrait
{
    //auth error codes
    private $authDeactivateUserErrorCode = 1;
    private $authUnverifiedUserErrorCode = 2;
    private $authRegStepOneErrorCode = 7;
    private $authRegStepTwoErrorCode = 8;
    private $authRegStepThreeErrorCode = 9;
    private $authRegStepFourErrorCode = 10;
    private $authBasicErrorCode = 3;
    //auth success code
    private $authSuccessCode = 4;

    //non allowed user error codes
    private $nonAllowedUserErrorCode = 5;

    //approval pending error code
    private $accountNotApprovedErrorCode = 6;
    private $accessTokenSpaDevice = 'spa';
    //account verification otp name prefix
    private $otpPrefix = 'otp_';
    // text error code
    private $NonAllowedAdminTextErrorCode   = 'user_non_admin';
    private $authUnverifiedUserTextErrorCode   = 'user_email_unverified';
    private $authWrongCredentialTextErrorCode = 'wrong_email_or_password';
    private $employeeAlreadyBranchAdminTextErrorCode = 'employee_already_branch_admin';
    private $authMerchantEmailNotExistsTextErrorCode = 'email_not_found';
    private $authMerchantPhoneNotExistsTextErrorCode = 'phone_not_found';
    private $authEmailAlreadyVerifiedUserTextErrorCode   = 'user_email_already_verified';
    private $authEmailNotVerifiedUserTextErrorCode   = 'user_email_not_verified';
    private $authMerchantUserTypeTextErrorCode   = 'user_merchant_type';
    private $authPhoneNotVerifiedUserTextErrorCode   = 'user_phone_not_verified';
    private $authPhoneAlreadyVerifiedUserTextErrorCode   = 'user_phone_already_verified';
    private $authMerchantAccountPendingTextErrorCode = "merchant_account_pending";
    private $authMerchantAccountBannedTextErrorCode = "merchant_account_banned";
    private $authMerchantAccountRejectedTextErrorCode = "merchant_account_rejected";
    private $authMerchantAccountDeactivateTextErrorCode = "merchant_account_deactivate";
    private $authExpiredCodeTextErrorCode = 'expired_code';
    private $authInvalidCodeTextErrorCode = 'invalid_code';

    private $authMerchantAccountNotPendingTextErrorCode = 'merchant_account_not_pending';

    private $authMerchantStep1NotCompleteTextErrorCode = 'merchant_step1_not_complete';
    private $authMerchantStep2NotCompleteTextErrorCode = 'merchant_step2_not_complete';
    private $authMerchantStep3NotCompleteTextErrorCode = 'merchant_step3_not_complete';
    private $authMerchantStep4AlreadyCompleteTextErrorCode = 'merchant_step4_already_complete';
    private $authMerchantStep3AlreadyCompleteTextErrorCode = 'merchant_step3_already_complete';
    private $authMerchantStep2AlreadyCompleteTextErrorCode = 'merchant_step2_already_complete';
    private $merchantAccountNotPendingMessage = 'Merchant Account Not Pending';
    private $merchantStep2AlreadyCompleteMessage = 'Merchant Step2 Already Complete';
    private $merchantStep1NotCompleteMessage = 'Merchant Step1 Not Complete';
    private $merchantStep2NotCompleteMessage = 'Merchant Step2 Not Complete';
    private $merchantStep3NotCompleteMessage = 'Merchant Step3 Not Complete';
    private $merchantStep4AlreadyCompleteMessage = 'Merchant Step4 Already Complete';

    private $merchantStep3AlreadyCompleteMessage = 'Merchant Step3 Already Complete';
    private $authExpiredCodeMessage = 'Expired Code!';
    private $authInvalidCodeMessage = 'Invalid Code!';

    // already email verified
    private $alreadyEmailVerifiedMessage = 'Email is Already verified!';
    // already phone verified
    private $alreadyPhoneVerifiedMessage = 'Phone is Already verified!';
    private $notEmailVerifiedMessage = 'Email is not verified!';
    private $merchantUserTypeMessage = 'Merchant User Not Found!';
    private $notPhoneVerifiedMessage='Phone is not verified!';
    private $merchantAccountPendingMessage = 'Merchant Account Pending';
    private $merchantAccountBannedMessage = 'Merchant Account Banned';
    private $merchantAccountRejectedMessage = 'Merchant Account Rejected';
    private $merchantAccountDeactivateMessage = 'Merchant Account Deactivate';


    //insert success
    private $insertSuccessMessage = 'Insert Success';
    //update success
    private $updateSuccessMessage = 'Update Success';
    private $deleteSuccessMessage = 'Delete Success';
    //fetch success
    private $fetchSuccessMessage = 'Fetch Success';
    private $otpSendMessage = 'Otp Send Successfully';
    //not found
    private $unverifiedUserErrorResponse = 'Please Verify Your Account to login';
    private $NonAllowedAdminErrorResponse = 'please try to login your application';

    private $emailVerifySuccessMessage = 'Email verification Completed!';
    private $phoneVerifySuccessMessage = 'Phone verification Completed!';

    private $employeeAlreadyBranchAdminMessage = "The Employee Already Admin In Another Branch.";

    /**
     * Email From mails And Subjects
     * */
    private $WebSiteName = "CTM";

    private $EmployeeRegisterMailFrom = "career@metroexpress.com.bd";
    private $InfoMailFrom = "info@metroexpress.com.bd";
    private $EmployeeRegisterMailName = "Metro Career";
    private $EmployeeRegisterMailSubject = "Thanks for Joining CTM!!";
    private $MerchantEmailVerifyMailSubject = "Merchant Email Verify Mail";
    private $EmployeeToBranchAdminMailSubject = "Welcome to Your New Role as Branch Admin";



}
