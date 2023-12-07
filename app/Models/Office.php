<?php

namespace App\Models;

use App\Http\Traits\PermissionTrait;
use Illuminate\Database\Eloquent\Builder;
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
 * @property int|null $divisionId
 * @property int|null $districtId
 * @property int|null $thanaId
 * @property string $nameEn
 * @property string $nameBn
 * @property int $officeType
 * @property string $officeAddress
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read \App\Models\Location|null $district
 * @property-read \App\Models\Location|null $division
 * @property-read \App\Models\Location|null $thana
 * @property int|null $parentId
 * @property int $version
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereVersion($value)
 * @property int|null $assignLocationId
 * @property-read \App\Models\Location|null $assignLocation
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereAssignLocationId($value)
 * @mixin \Eloquent
 */
class Office extends Model
{
    use PermissionTrait;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // echo 'Constr'.$data;
        // print_r($data);

        // print_r($data);

        static::addGlobalScope('assign_location_type', function (Builder $builder) {
            $data = $this->getUserPermissions();
            // echo 'Constr'.$data;
            $builder->whereHas('assignLocation', function ($query) use ($data) {
                // echo 'Constr'.$data;
                // $data = 'division';
                // $query
                // // ->where('type', 'division')
                // // ->where('id', '6')
                // ->where('type', 'division')
                // ->orwhere('type', 'district')
                // ->whereHas('parent', function ($query) {
                //     $query->where('type', 'division')->where('id', '6');
                // });

                //     // ->orWhere('type', 'thana')

                //     // ->orWhere('type', 'city_corporation')
                //     // ->orWhere('type', 'union')
                //     // ->orWhere('type', 'ward')
                // ;

                // $data = 4; // barisal id
                // $data = 6; // dhaka id
                if ($data != false) {
                    # code...
                if ($data['type'] == 'division') {
                    $query->where(function ($query) use ($data) {
                        $query->whereHas('parent', function ($query) use ($data) {
                            $query->where('id', $data['location_id'])
                                ->orWhereHas('parent', function ($query) use ($data) {
                                    $query->where('id', $data['location_id'])
                                        ->orWhereHas('parent', function ($query) use ($data) {
                                            $query->where('id', $data['location_id'])
                                                ->orWhereHas('parent', function ($query) use ($data) {
                                                    $query->where('id', $data['location_id'])
                                                        ->where('type', $data['type']);

                                                    // $query->where('id', $data)
                                                    // ->where('type', 'thana');
                                                });
                                        });
                                });
                        });
                    })
                    ->orWhere('type', $data['type']);
                }
                if ($data['type'] == 'district') {
                    $query->where(function ($query) use ($data) {
                        $query->whereHas('parent', function ($query) use ($data) {
                            $query->where('id', $data['location_id'])
                                ->orWhereHas('parent', function ($query) use ($data) {
                                    $query->where('id', $data['location_id'])
                                        ->orWhereHas('parent', function ($query) use ($data) {
                                            $query->where('id', $data['location_id'])
                                                ->orWhereHas('parent', function ($query) use ($data) {
                                                    $query->where('id', $data['location_id'])
                                                        ->where('type', $data['type']);

                                                });
                                        });
                                });
                        });
                    })
                    ->orWhere('type', $data['type']);
                }
                if ($data['type'] == 'thana') {
                    $query->where(function ($query) use ($data) {
                        $query->whereHas('parent', function ($query) use ($data) {
                            $query->where('id', $data['location_id'])
                                ->orWhereHas('parent', function ($query) use ($data) {
                                    $query->where('id', $data['location_id'])
                                        ->orWhereHas('parent', function ($query) use ($data) {
                                            $query->where('id', $data['location_id'])
                                                ->orWhereHas('parent', function ($query) use ($data) {
                                                    $query->where('id', $data['location_id'])
                                                        ->where('type', $data['type']);

                                                });
                                        });
                                });
                        });
                    })
                    ->orWhere('type', $data['type']);
                }
            }



                // $query->where(function ($query) use ($data) {
                //     $query->where('type', 'thana')
                //         ->whereHas('parent', function ($query) use ($data) {
                //             $query->where('type', 'district')->whereHas('parent', function ($query) {
                //                 $query->where('type', 'division')->where('id', '6');
                //             });
                //         });
                // })
                // ->orWhere('type', 'division');

                // {
                //     $query->where($parent3filterArrayNameEn)
                //         ->orWhere($parent3filterArrayNameBn)
                //         ->orWhere($parent3filterArrayCode) // City Level Search

                //         ->orWhereHas('parent', function ($query) use (
                //             $parent2filterArrayNameEn,
                //             $parent2filterArrayNameBn,
                //             $parent2filterArrayCode,
                //             $parent1filterArrayNameEn,
                //             $parent1filterArrayNameBn,
                //             $parent1filterArrayCode
                //         ) {
                //             $query->where($parent2filterArrayNameEn)
                //                 ->orWhere($parent2filterArrayNameBn)
                //                 ->orWhere($parent2filterArrayCode) // District Level Search

                //                 ->orWhereHas('parent', function ($query) use ($parent1filterArrayNameEn, $parent1filterArrayNameBn, $parent1filterArrayCode) {
                //                     $query->where($parent1filterArrayNameEn)
                //                         ->orWhere($parent1filterArrayNameBn)
                //                         ->orWhere($parent1filterArrayCode); // Division Level Search
                //                 });
                //         });
                // });
            });

            // -------------------

            // if ($data['type'] == 'division') {

            // } elseif ($data == 'district') {
            //     $query->where('type', 'district')->orWhere('type', 'thana')->orWhere('type', 'city_corporation')->orWhere('type', 'union')->orWhere('type', 'ward');
            // } elseif ($data == 'thana') {
            //     $query->where('type', 'thana')->orWhere('type', 'city_corporation')->orWhere('type', 'union')->orWhere('type', 'ward');
            // } elseif ($data == 'city_corporation') {
            //     $query->where('type', 'city_corporation')->orWhere('type', 'union')->orWhere('type', 'ward');
            // } elseif ($data == 'union') {
            //     $query->where('type', 'union')->orWhere('type', 'ward');
            // } elseif ($data == 'ward') {
            //     $query->where('type', 'ward');
            // }
        });
    }

    // public function getUserPermissions()
    // {
    //     $this->user_id = request()->user_id;
    //     $this->office_location_id = request()->office_location_id;

    //     $user = User::findOrFail($this->user_id);
    //     // echo $user->office->office_type;
    //     // echo $user->assign_location->type;

    //     if ($user->user_type == $this->superAdminId) {
    //         return false;
    //     }

    //     if ($user->user_type == $this->staffId) {
    //         $officeHead = User::where('office_id', $user->office_id)->whereHas('roles', function ($query) {
    //             $query->where('name', $this->officeHead);
    //         })->first();

    //         // print_r($officeHead);
    //         if ($officeHead) {
    //             // IS OFFICE HEAD
    //             $data = array(
    //                 'type' => $officeHead->assign_location->type,
    //                 'location_id' => $officeHead->assign_location->id,
    //             );
    //             return $data;
    //             // return $officeHead->assign_location->type; // Office Head
    //         } else {
    //             // NOT OFFICE HEAD
    //             $data = array(
    //                 'type' => $officeHead->assign_location->type,
    //                 'location_id' => $officeHead->assign_location->id,
    //             );
    //             // return $user->assign_location->type; // Office Staff
    //             return $data;
    //         }
    //     }
    // }

    public function newQuery($excludeDeleted = true)
    {
        return parent::newQuery($excludeDeleted)->orderBy('name_en', 'asc');
    }

    public function officeType()
    {
        return $this->belongsTo(Lookup::class, 'office_type');
    }

    public function assignLocation()
    {
        return $this->belongsTo(Location::class, 'assign_location_id');
    }

    public function wards()
    {
        return $this->hasMany(OfficeHasWard::class, 'office_id');
    }

    public function ward_location()
    {
        return $this->belongsTo(Location::class, 'parent_id');
    }
}
