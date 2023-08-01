<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Office
 *
 * @property int $id
 * @property int|null $division_id
 * @property int|null $district_id
 * @property int|null $thana_id
 * @property string $name_en
 * @property string $name_bn
 * @property int $office_type
 * @property string $office_address
 * @property string|null $comment
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Office newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Office newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Office query()
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereDistrictId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereDivisionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereNameBn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereOfficeAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereOfficeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereThanaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Office extends Model
{

    public function division()
    {
        return $this->belongsTo(Location::class,'division_id');
    }
    public function district()
    {
        return $this->belongsTo(Location::class,'district_id');
    }
    public function thana()
    {
        return $this->belongsTo(Location::class,'thana_id');
    }
}
