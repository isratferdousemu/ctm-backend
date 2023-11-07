<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Location> $children
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Location> $children
 * @property int|null $parentId
 * @property string $nameEn
 * @property string $nameBn
 * @property int|null $createdBy
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property \Illuminate\Support\Carbon|null $deletedAt
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Location> $children
 * @property-read int|null $childrenCount
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Location> $children
 * @property int|null $locationType
 * @property-read \App\Models\Lookup|null $locationType
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereLocationType($value)
 * @mixin \Eloquent
 */
class Location extends Model
{
    use HasFactory,SoftDeletes;
    // protected $defaultOrderColumn = 'name_en';
    // protected $defaultOrderDirection = 'asc';

    // public function newQuery($excludeDeleted = true)
    // {
    //     return parent::newQuery($excludeDeleted)
    //         ->orderBy('name_en', 'asc');
    // }
    public function children()
    {
        return $this->hasMany(Location::class, 'parent_id');
    }


    public function parent()
    {
        return $this->belongsTo(Location::class, 'parent_id') ->orderBy('name_en', 'asc');
    }
    public function locationType()
    {
        return $this->belongsTo(Lookup::class, 'location_type');
    }

    public function office()
    {
        return $this->hasMany(Office::class, 'assign_location_id');
    }

}
