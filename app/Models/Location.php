<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Location
 *
 * @property int $id
 * @property int|null $parent_id
 * @property string $code
 * @property string $name_en
 * @property string $name_bn
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Location> $children
 * @property-read int|null $children_count
 * @property-read Location|null $parent
 * @method static \Illuminate\Database\Eloquent\Builder|Location newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Location newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Location query()
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereNameBn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereUpdatedAt($value)
 * @property string $type
 * @property int $version
 * @property int|null $created_by
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Location> $children
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereVersion($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Location> $children
 * @method static \Illuminate\Database\Eloquent\Builder|Location onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Location withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Location withoutTrashed()
 * @mixin \Eloquent
 */
class Location extends Model
{
    use HasFactory,SoftDeletes;



    public function children()
    {
        return $this->hasMany(Location::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Location::class, 'parent_id');
    }
}
