<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Traits\MessageTrait;
use App\Models\Bank;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GlobalController extends Controller
{
    use MessageTrait;



    public function insertLocation(Request $request){
        // $division = Location::create([
        //     'parent_id' => $request->division_id,
        //     'name_en' => 'district 1',
        //     'name_bn' => 'district',
        //     'code' => '44ddw4',
        // ]);
        // $division = Location::get();
        $division = Location::with('parent')->where('parent_id', 1)->get();
        return $division;

    }
}
