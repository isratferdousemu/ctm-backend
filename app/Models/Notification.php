<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Notification
 *
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification query()
 * @mixin \Eloquent
 */
class Notification extends Model
{
    use HasFactory;
    protected $fillable = ['message', 'user_id', 'is_read'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
