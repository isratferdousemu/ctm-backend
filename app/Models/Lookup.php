<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Lookup
 *
 * @property int $id
 * @property int $type
 * @property string $valueEn
 * @property string $valueBn
 * @property string|null $keyword
 * @property int $version
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @method static \Illuminate\Database\Eloquent\Builder|Lookup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Lookup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Lookup query()
 * @method static \Illuminate\Database\Eloquent\Builder|Lookup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lookup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lookup whereKeyword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lookup whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lookup whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lookup whereValueBn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lookup whereValueEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lookup whereVersion($value)
 * @property int $default
 * @method static \Illuminate\Database\Eloquent\Builder|Lookup whereDefault($value)
 * @mixin \Eloquent
 */
class Lookup extends Model
{
    use HasFactory;
}
