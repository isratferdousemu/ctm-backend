<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AllowanceProgramAdditionalField
 *
 * @property int $id
 * @property int $allowanceProgramId
 * @property int $additionalFieldId
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgramAdditionalField newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgramAdditionalField newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgramAdditionalField query()
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgramAdditionalField whereAdditionalFieldId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgramAdditionalField whereAllowanceProgramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgramAdditionalField whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgramAdditionalField whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgramAdditionalField whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AllowanceProgramAdditionalField extends Model
{
    use HasFactory;
}
