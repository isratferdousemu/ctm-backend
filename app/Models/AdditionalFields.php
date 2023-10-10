<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AdditionalFields
 *
 * @property int $id
 * @property string $nameEn
 * @property string $nameBn
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalFields newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalFields newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalFields query()
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalFields whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalFields whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalFields whereNameBn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalFields whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalFields whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdditionalFields whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AdditionalFields extends Model
{
    use HasFactory;
    
}
