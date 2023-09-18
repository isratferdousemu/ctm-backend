<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CommitteeMember
 *
 * @property int $id
 * @property int $committeeId
 * @property string $memberName
 * @property string $designation
 * @property string|null $email
 * @property string|null $address
 * @property int $phone
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read \App\Models\Committee $committee
 * @method static \Illuminate\Database\Eloquent\Builder|CommitteeMember newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CommitteeMember newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CommitteeMember query()
 * @method static \Illuminate\Database\Eloquent\Builder|CommitteeMember whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommitteeMember whereCommitteeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommitteeMember whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommitteeMember whereDesignation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommitteeMember whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommitteeMember whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommitteeMember whereMemberName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommitteeMember wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommitteeMember whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CommitteeMember extends Model
{
    
    public function committee(){

        return $this->belongsTo(Committee::class,'committee_id');

    }
}
