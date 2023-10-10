<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AllowanceProgramGender
 *
 * @property int $id
 * @property int $allowanceProgramId
 * @property int $genderId
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgramGender newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgramGender newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgramGender query()
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgramGender whereAllowanceProgramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgramGender whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgramGender whereGenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgramGender whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgramGender whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AllowanceProgramGender extends Model
{
    use HasFactory;
}
