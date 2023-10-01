<?php

namespace App\Http\Traits;

trait OfficeTrait
{
    // Default Office Types
    private $ministryType = 1;
    private $headOfficeType = 2;
    private $divisionType = 3;
    private $districtType = 4;
    private $upazilaType = 5;
    private $UCDType = 6;
    private $upazilaUcdType = 7;
    private $circleSocialServiceType = 8;

    public static function getOfficeTypes()
    {
        $types = [
            ['id' => 1, 'name' => 'Ministry'],
            ['id' => 2, 'name' => 'Head Office'],
            ['id' => 3, 'name' => 'Division'],
            ['id' => 4, 'name' => 'District'],
            ['id' => 5, 'name' => 'Upazila'],
            ['id' => 6, 'name' => 'UCD'],
            ['id' => 7, 'name' => 'Upazila UCD'],
            ['id' => 8, 'name' => 'Circle Social Service'],
        ];

        return collect($types);
    }
}
