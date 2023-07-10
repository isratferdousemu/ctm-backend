<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AllowanceProgram
 *
 * @property int $id
 * @property string $code
 * @property string $name_en
 * @property string $name_bn
 * @property string|null $guideline
 * @property int $service_type
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgram newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgram newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgram query()
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgram whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgram whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgram whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgram whereGuideline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgram whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgram whereNameBn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgram whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgram whereServiceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AllowanceProgram whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AllowanceProgram extends Model
{
    use HasFactory;
}
