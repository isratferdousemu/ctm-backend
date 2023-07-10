<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,SoftDeletes,HasRoles;
    protected $guard_name = 'sanctum';


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
