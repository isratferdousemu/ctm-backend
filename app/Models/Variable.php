<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Variable
 *
 * @property int $id
 * @property int|null $parentId
 * @property string $nameEn
 * @property float|null $score
 * @property int $fieldType
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property string|null $deletedAt
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Variable> $children
 * @property-read int|null $childrenCount
 * @property-read Variable|null $parent
 * @method static \Illuminate\Database\Eloquent\Builder|Variable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Variable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Variable query()
 * @method static \Illuminate\Database\Eloquent\Builder|Variable whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variable whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variable whereFieldType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variable whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variable whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variable whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variable whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variable whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Variable extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'score',
    ];

    public function parent()
    {
        return $this->belongsTo(Variable::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Variable::class, 'parent_id');
    }
}
