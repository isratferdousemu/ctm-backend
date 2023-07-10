<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string|null $full_name
 * @property string|null $email
 * @property string|null $phone
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property int|null $user_type
 * @property string|null $remember_token
 * @property int $merchent_step_1
 * @property int $merchent_step_2
 * @property int $merchent_step_3
 * @property int $merchent_step_4
 * @property string|null $last_login_at
 * @property int $status
 * @property string|null $profile
 * @property int|null $payment_type
 * @property int|null $wallet_type
 * @property int|null $wallet_number
 * @property string|null $bank_name
 * @property string|null $bank_branch_name
 * @property string|null $bank_account_type
 * @property int|null $bank_routing_num
 * @property string|null $bank_account_holder_name
 * @property int|null $bank_account_num
 * @property int|null $account_type
 * @property int|null $branch_id
 * @property int|null $department_id
 * @property int|null $employee_id
 * @property int|null $rider_id
 * @property int|null $merchant_id
 * @property string|null $date_of_birth
 * @property string|null $join_date
 * @property string|null $address
 * @property int|null $employee_shift_id
 * @property string|null $gender
 * @property int|null $gurdian_contact
 * @property string|null $father_name
 * @property string|null $mother_name
 * @property string|null $present_address
 * @property string|null $permanent_address
 * @property int|null $salary_type
 * @property int|null $comission_type
 * @property int|null $salary
 * @property int|null $comission
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Query\Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAccountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBankAccountHolderName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBankAccountNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBankAccountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBankBranchName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBankRoutingNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereComission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereComissionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmployeeShiftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFatherName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGurdianContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereJoinDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastLoginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMerchantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMerchentStep1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMerchentStep2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMerchentStep3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMerchentStep4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMotherName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePaymentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePermanentAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePresentAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRiderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSalaryType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUserType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereWalletNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereWalletType($value)
 * @method static \Illuminate\Database\Query\Builder|User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|User withoutTrashed()
 * @property-read \App\Models\WorkingShift|null $WorkingShift
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\Department|null $department
 * @property string|null $phone_verified_at
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhoneVerifiedAt($value)
 * @property int|null $bank_id
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBankId($value)
 * @property int|null $division_id
 * @property int|null $district_id
 * @property int|null $thana_id
 * @property string $username
 * @property string|null $mobile
 * @property int|null $office_id
 * @property int|null $assign_location_id
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAssignLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDistrictId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDivisionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereOfficeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereThanaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,SoftDeletes,HasRoles;
    protected $guard_name = 'sanctum';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public static $MerchantVerificationRules = [

        'userid'                              => 'required',
        'code'                               => 'required|numeric|min:5',
    ];
    public static $MerchantPasswordRules = [
        // password must be at least 8 characters and contain at least one uppercase letter, one lowercase letter and one digit

        'userid'                              => 'required',
        'password'                               => 'required|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/|max:20',
    ];

    // bd phone number validation with regex pattern and starts with 01 or 880 or +880 and 11 digits long and numeric only
    public static $merchantDetailsRules = [
        'full_name'                              => 'required',
        'shop_name' => 'required|unique:shops,shop_name|max:255',
        'link'  => "sometimes|nullable|string",
        'product_category_id'=>"required|exists:product_categories,id",
        "pickup_phone"=>["required","unique:shops,pickup_phone","max:13","regex:/^(?:\+?88|01)?(?:\d{11}|\d{13})$/"],
        "pickup_address"=>"required",
        "area_id"=>"required|exists:areas,id",

    ];

    // merchant payment details
    public static $merchantPaymentDetailsRules = [
        'userid'                              => 'required',
        'payment_type'                              => 'required|in:1,2',
        'wallet_type'                           => 'required_if:payment_type,==,1|in:1,2,3|nullable',
        'wallet_number'  => ['required_if:payment_type,==,1',"max:14","regex:/^(?:\+?88|01)?(?:\d{11}|\d{13})$/","nullable"],
        "bank_id"=>"required_if:payment_type,==,2|exists:banks,id|nullable",
        "bank_branch_name"=>"required_if:payment_type,==,2|string|nullable",
        "bank_account_type"=>"required_if:payment_type,==,2|in:1,2|nullable",
        "bank_routing_num"=>"required_if:payment_type,==,2|int|nullable",
        "bank_account_holder_name"=>"required_if:payment_type,==,2|string|nullable",
        "bank_account_num"=>"required_if:payment_type,==,2|int|nullable",
    ];

    public static $merchantLoginRules = [
                'userid'                => 'required',
                'password'             => ["required","min:8","regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/","max:20"],
                'device'               => 'required|string',
    ];

    
}
