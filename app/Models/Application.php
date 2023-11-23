<?php

namespace App\Models;

use App\Models\Variable;
use Illuminate\Database\Eloquent\Model;
use App\Models\ApplicationPovertyValues;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Application
 *
 * @property int $id
 * @property string $applicationId
 * @property int|null $forwardCommitteeId
 * @property string|null $remark
 * @property int $programId
 * @property string $verificationType
 * @property string $verificationNumber
 * @property int $age
 * @property string $dateOfBirth
 * @property string $nameEn
 * @property string $nameBn
 * @property string $motherNameEn
 * @property string $motherNameBn
 * @property string $fatherNameEn
 * @property string $fatherNameBn
 * @property string $spouseNameEn
 * @property string $spouseNameBn
 * @property string $identificationMark
 * @property string $image
 * @property string $signature
 * @property string $nationality
 * @property int $genderId
 * @property string $educationStatus
 * @property string $profession
 * @property string $religion
 * @property int $currentLocationId
 * @property string $currentPostCode
 * @property string $currentAddress
 * @property string $mobile
 * @property int $permanentLocationId
 * @property string $permanentPostCode
 * @property string $permanentAddress
 * @property string $permanentMobile
 * @property string $nomineeEn
 * @property string $nomineeBn
 * @property string $nomineeVerificationNumber
 * @property string $nomineeAddress
 * @property string $nomineeImage
 * @property string $nomineeSignature
 * @property string $nomineeRelationWithBeneficiary
 * @property string $nomineeNationality
 * @property string $accountName
 * @property string $accountNumber
 * @property string $accountOwner
 * @property string $maritalStatus
 * @property string $email
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @method static \Illuminate\Database\Eloquent\Builder|Application newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Application newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Application query()
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereAccountName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereAccountOwner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereAge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereApplicationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereCurrentAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereCurrentLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereCurrentPostCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereEducationStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereFatherNameBn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereFatherNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereForwardCommitteeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereGenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereIdentificationMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereMaritalStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereMotherNameBn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereMotherNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereNameBn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereNationality($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereNomineeAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereNomineeBn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereNomineeEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereNomineeImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereNomineeNationality($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereNomineeRelationWithBeneficiary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereNomineeSignature($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereNomineeVerificationNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application wherePermanentAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application wherePermanentLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application wherePermanentMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application wherePermanentPostCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereProfession($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereProgramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereReligion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereSignature($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereSpouseNameBn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereSpouseNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereVerificationNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereVerificationType($value)
 * @mixin \Eloquent
 */
class Application extends Model
{
    use HasFactory;

    // hide these fields from json response
    // protected $hidden = ['score'];

    public function newQuery($excludeDeleted = true)
    {
        return parent::newQuery($excludeDeleted);
            // ->orderBy('score', 'asc');
    }


    public static function permanentDistrict($location_id){
        // permanent_location_id get this parent_id parent_id rations location id by maintaining chain
        $permanentLocation = Location::find($location_id);
        // check location type and then again and again check parent id and type while not get type = district
        while($permanentLocation->type != 'district'){
            $permanentLocation = Location::find($permanentLocation->parent_id);
        }
        return $permanentLocation;
    }

    public function current_location()
    {
        return $this->belongsTo(Location::class,'current_location_id','id');
    }
    public function permanent_location()
    {
        return $this->belongsTo(Location::class,'permanent_location_id','id');
    }

    public function program(){
        return $this->belongsTo(AllowanceProgram::class,'program_id','id');
    }
    public function gender(){
        return $this->belongsTo(Lookup::class,'gender_id','id');
    }

    public function application()
    {
        return $this->belongsTo(ApplicationPovertyValues::class, 
        'id');
    }
    
    
    // public function variable()
    // {
    //     return $this->belongsToMany(Variable::class, 
    //     'parent_id');
    // }
    
    public function poverty_score() //emu
    {
        return $this->belongsToMany(Variable::class, 'application_poverty_values', 'application_id','variable_id');
    }
       public function poverty_score_value() //emu
    {
        return $this->belongsToMany(Variable::class, 'application_poverty_values', 'application_id','sub_variable_id');
    }
  
//    public function povertyValues()
//     {
//         return $this->hasMany(ApplicationPovertyValues::class, 'application_id');
//     }
   

}
