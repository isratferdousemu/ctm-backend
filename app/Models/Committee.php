<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Committee
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $details
 * @property int $programId
 * @property int $divisionId
 * @property int $districtId
 * @property int $officeId
 * @property int|null $locationId
 * @property int $version
 * @property string|null $deletedAt
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read \App\Models\Location|null $district
 * @property-read \App\Models\Location|null $division
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CommitteeMember> $members
 * @property-read int|null $membersCount
 * @property-read \App\Models\Office|null $office
 * @property-read \App\Models\AllowanceProgram|null $program
 * @method static \Illuminate\Database\Eloquent\Builder|Committee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Committee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Committee query()
 * @method static \Illuminate\Database\Eloquent\Builder|Committee whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Committee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Committee whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Committee whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Committee whereDistrictId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Committee whereDivisionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Committee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Committee whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Committee whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Committee whereOfficeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Committee whereProgramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Committee whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Committee whereVersion($value)
 * @mixin \Eloquent
 */
class Committee extends Model
{

    public function division(){

        return $this->belongsTo(Location::class,'division_id');

    }
    public function district(){

        return $this->belongsTo(Location::class,'district_id');

    }
    public function program(){

        return $this->belongsTo(AllowanceProgram::class,'program_id');

    }
    public function office(){

        return $this->belongsTo(Office::class,'office_id');

    }
    public function members(){
        return $this->hasMany(CommitteeMember::class);

       

    }
}
