<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AdditionalFieldValues
 *
 * @property int $id
 * @property int $additionalFieldId
 * @property string|null $value
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalFieldValues newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalFieldValues newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalFieldValues query()
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalFieldValues whereAdditionalFieldId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalFieldValues whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalFieldValues whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalFieldValues whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalFieldValues whereValue($value)
 * @mixin \Eloquent
 */
class AdditionalFieldValues extends Model
{
    use HasFactory;
}
