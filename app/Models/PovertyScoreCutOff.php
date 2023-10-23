<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\PovertyScoreCutOff
 *
 * @property int $id
 * @property int $location_id
 * @property float|null $score
 * @property \Illuminate\Support\Carbon|null $deletedAt
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read \Illuminate\Database\Eloquent\Collection<int, PovertyScoreCutOff> $children
 * @method static \Illuminate\Database\Eloquent\Builder|PovertyScoreCutOff newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PovertyScoreCutOff newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PovertyScoreCutOff onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|PovertyScoreCutOff query()
 * @method static \Illuminate\Database\Eloquent\Builder|PovertyScoreCutOff whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PovertyScoreCutOff whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PovertyScoreCutOff whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PovertyScoreCutOff whereLabelNameBn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PovertyScoreCutOff whereLabelNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PovertyScoreCutOff whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PovertyScoreCutOff wherePageLinkId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PovertyScoreCutOff whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PovertyScoreCutOff whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PovertyScoreCutOff withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|PovertyScoreCutOff withoutTrashed()
 * @property string $linkType
 * @property string|null $link
 * @method static \Illuminate\Database\Eloquent\Builder|PovertyScoreCutOff whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PovertyScoreCutOff whereLinkType($value)
 * @mixin \Eloquent
 */

class PovertyScoreCutOff extends Model
{
    use HasFactory;
    public function newQuery($excludeDeleted = true)
    {
        return parent::newQuery($excludeDeleted)
            ->orderBy('score', 'asc');
    }
    protected $table = 'poverty_score_cut_offs';

    protected $fillable = [
        'location_id',
        'score',
    ];

    public function assign_location()
    {
        return $this->belongsTo(Location::class, 'id');
    }
}
