<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Traits\MessageTrait;
use App\Http\Controllers\Controller;
use App\Http\Services\Admin\Systemconfig\SystemconfigService;
use App\Models\Application;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class SystemconfigDashboardController extends Controller
{
    use MessageTrait;
    private $systemconfigService;

    public function __construct(SystemconfigService $systemconfigService) {
        $this->systemconfigService= $systemconfigService;
    }

    public function getAllLocationApplicationCount(Request $request){

        $a = Location::where('type','division')->groupBy('type')->count();


        $counts = [
            'permanent_division_count' => Location::where('type','division')->groupBy('type')->count(),
            'permanent_district_count' => Location::where('type','district')->groupBy('type')->count(),

            'permanent_district_pourashava_count' => Location::where('type','city')->where('location_type',1)->groupBy('type')->count(),
            'permanent_city_corp_count' => Location::where('type','city')->where('location_type',3)->groupBy('type')->count(),
            'permanent_thana_count' => Location::where('type','thana')->where('location_type',3)->groupBy('type')->count(),
            'permanent_upazila_count' => Location::where('type','thana')->where('location_type',2)->groupBy('type')->count(),
            'permanent_union_count' => Location::where('type','union')->where('location_type',2)->groupBy('type')->count(),
            'permanent_pourashava_count' => Location::where('type','pouro')->where('location_type',2)->groupBy('type')->count(),
            'permanent_ward_count' => Location::where('type','ward')->groupBy('type')->count(),
        ];

        $items = [
            ['title' => 'Division', 'number' => $counts['permanent_division_count']],
            ['title' => 'District', 'number' => $counts['permanent_district_count']],
            ['title' => 'Upazila', 'number' => $counts['permanent_upazila_count']],
            ['title' => 'City Cor.', 'number' => $counts['permanent_city_corp_count']],
            ['title' => 'Dist/Pau', 'number' => $counts['permanent_district_pourashava_count']],
            ['title' => 'Union', 'number' => $counts['permanent_union_count']],
            ['title' => 'Thana', 'number' => $counts['permanent_thana_count']],
            ['title' => 'Paurashava', 'number' => $counts['permanent_pourashava_count']],
            ['title' => 'Ward', 'number' => $counts['permanent_ward_count']],
        ];
        return response()->json([
            'data' => $items,
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ], ResponseAlias::HTTP_OK);
    }

}
