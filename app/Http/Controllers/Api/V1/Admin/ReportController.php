<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Geographic\CityResource;
use App\Http\Resources\Admin\Geographic\UnionResource;
use App\Http\Traits\LocationTrait;
use App\Http\Traits\MessageTrait;
use App\Http\Traits\UserTrait;
use App\Models\Location;
use Illuminate\Http\Request;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf;

class ReportController extends Controller
{
    use MessageTrait, UserTrait, LocationTrait;
    public function getDivisions($request)
    {
        $searchText = $request->query('searchText');
        $sortBy = $request->query('sortBy') ?? 'name_en';
        $orderBy = $request->query('orderBy') ?? 'asc';

        $filterArrayNameEn = [];
        $filterArrayNameBn = [];
        $filterArrayCode = [];

        if ($searchText) {
            $filterArrayNameEn[] = ['name_en', 'LIKE', '%' . $searchText . '%'];
            $filterArrayNameBn[] = ['name_bn', 'LIKE', '%' . $searchText . '%'];
            $filterArrayCode[] = ['code', 'LIKE', '%' . $searchText . '%'];
        }

        return Location::query()
            ->where(function ($query) use ($filterArrayNameEn, $filterArrayNameBn, $filterArrayCode) {
                $query->where($filterArrayNameEn)
                    ->orWhere($filterArrayNameBn)
                    ->orWhere($filterArrayCode);
            })
            ->whereParentId(null)
            ->orderBy($sortBy, $orderBy)
            ->get();
    }


    public function divisionReport(Request $request)
    {
        $divisions = $this->getDivisions($request);

        $data = ['divisions' => $divisions];

        $pdf = LaravelMpdf::loadView('reports.division', $data, [],
            [
                'mode' => 'utf-8',
                'format' => 'A4-P',
                'title' => 'বিভাগের তালিকা',
                'orientation' => 'L',
                'default_font_size' => 10,
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 10,
                'margin_bottom' => 10,
                'margin_header' => 10,
                'margin_footer' => 10,
            ]);


        $fileName = 'বিভাগের_তালিকা_' . now()->timestamp . '_'. auth()->id() . '.pdf';

        $pdfPath = public_path("/pdf/$fileName");

        $pdf->save($pdfPath);

        return $this->sendResponse(['url' => asset("/pdf/$fileName")]);
    }


    public function getDistricts($request)
    {
        // Retrieve the query parameters
        $searchText = $request->query('searchText');
        $sortBy = $request->query('sortBy') ?? 'name_en';
        $orderBy = $request->query('orderBy') ?? 'asc';

        $filterArrayNameEn = [];
        $filterArrayNameBn = [];
        $filterArrayCode = [];

        $parent1filterArrayNameEn = [];
        $parent1filterArrayNameBn = [];
        $parent1filterArrayCode = [];

        if ($searchText) {
            $filterArrayNameEn[] = ['locations.name_en', 'LIKE', '%' . $searchText . '%'];
            $filterArrayNameBn[] = ['locations.name_bn', 'LIKE', '%' . $searchText . '%'];
            $filterArrayCode[] = ['locations.code', 'LIKE', '%' . $searchText . '%'];

            $parent1filterArrayNameEn[] = ['parent1.name_en', 'LIKE', '%' . $searchText . '%'];
            $parent1filterArrayNameBn[] = ['parent1.name_bn', 'LIKE', '%' . $searchText . '%'];
            $parent1filterArrayCode[] = ['parent1.code', 'LIKE', '%' . $searchText . '%'];

        }

        // Level 3
        if ($sortBy == 'name_en') {
            $sortBy = 'name_en';
        }
        // Level 2
        if ($sortBy == 'parent.name_en') {
            $sortBy = 'parent1.name_en';
        }
        // Level 3
        if ($sortBy == 'name_bn') {
            $sortBy = 'name_bn';
        }
        // Level 2
        if ($sortBy == 'parent.name_bn') {
            $sortBy = 'parent1.name_bn';
        }

        $district = Location::query()
            ->join('locations as parent1', 'locations.parent_id', '=', 'parent1.id') // Join with the parent table
            ->select(
                'locations.*',
            )
            ->where(function ($query) use (
                $parent1filterArrayNameEn,
                $parent1filterArrayNameBn,
                $parent1filterArrayCode,
                $filterArrayNameEn,
                $filterArrayNameBn,
                $filterArrayCode
            ) {
                $query->where($filterArrayNameEn)
                    ->orWhere($filterArrayNameBn)
                    ->orWhere($filterArrayCode)

                    ->orWhereHas('parent', function ($query) use (
                        $parent1filterArrayNameEn,
                        $parent1filterArrayNameBn,
                        $parent1filterArrayCode,
                    ) {
                        $query->where($parent1filterArrayNameEn)
                            ->orWhere($parent1filterArrayNameBn)
                            ->orWhere($parent1filterArrayCode); // District Search
                    });
            })
            ->where('locations.type', '=', $this->district)
            ->orderBy($sortBy, $orderBy)
            ->with('parent')
            ->get()
            ;

        return $district;
    }



    public function districtReport(Request $request)
    {
        $districts = $this->getDistricts($request);

        $data = ['districts' => $districts];

        $pdf = LaravelMpdf::loadView('reports.district', $data, [],
            [
                'mode' => 'utf-8',
                'format' => 'A4-P',
                'title' => 'জেলার তালিকা',
                'orientation' => 'L',
                'default_font_size' => 10,
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 10,
                'margin_bottom' => 10,
                'margin_header' => 10,
                'margin_footer' => 10,
            ]);


        $fileName = 'জেলার_তালিকা_' . now()->timestamp . '_'. auth()->id() . '.pdf';

        $pdfPath = public_path("/pdf/$fileName");

        $pdf->save($pdfPath);

        return $this->sendResponse(['url' => asset("/pdf/$fileName")]);
    }


    public function getCityList($request)
    {
        // Retrieve the query parameters

        //Filter
        $division_id = $request->query('division_id');
        $district_id = $request->query('district_id');
        $location_type = $request->query('location_type');
        //Filter


        $searchText = $request->query('searchText');
        $sortBy = $request->query('sortBy') ?? 'name_en';
        $orderBy = $request->query('orderBy') ?? 'asc';

        $filterArrayNameEn = [];
        $filterArrayNameBn = [];
        $filterArrayCode = [];

        $parent2filterArrayNameEn = [];
        $parent2filterArrayNameBn = [];
        $parent2filterArrayCode = [];

        $parent1filterArrayNameEn = [];
        $parent1filterArrayNameBn = [];
        $parent1filterArrayCode = [];


        if ($searchText) {
            $filterArrayNameEn[] = ['locations.name_en', 'LIKE', '%' . $searchText . '%'];
            $filterArrayNameBn[] = ['locations.name_bn', 'LIKE', '%' . $searchText . '%'];
            $filterArrayCode[]   = ['locations.code', 'LIKE', '%' . $searchText . '%'];

            $parent2filterArrayNameEn[] = ['parent2.name_en', 'LIKE', '%' . $searchText . '%'];
            $parent2filterArrayNameBn[] = ['parent2.name_bn', 'LIKE', '%' . $searchText . '%'];
            $parent2filterArrayCode[]   = ['parent2.code', 'LIKE', '%' . $searchText . '%'];

            $parent1filterArrayNameEn[] = ['parent1.name_en', 'LIKE', '%' . $searchText . '%'];
            $parent1filterArrayNameBn[] = ['parent1.name_bn', 'LIKE', '%' . $searchText . '%'];
            $parent1filterArrayCode[]   = ['parent1.code', 'LIKE', '%' . $searchText . '%'];

        }


        //
        // this is a 3 Level Search/Sorting
        // so this will start from name which is at level 3
        // then parent.name which is at level 2
        // then parent.parent.name which is at level 1
        //

        // Level 3
        if ($sortBy == 'name_en') {
            $sortBy = 'name_en';
        }
        // Level 2
        if ($sortBy == 'parent.name_en') {
            $sortBy = 'parent2.name_en';
        }
        // Level 1
        if ($sortBy == 'parent.parent.name_en') {
            $sortBy = 'parent1.name_en';
        }

        ///
        // parent4
        // parent3
        // parent2
        // parent1
        /// JOIN and Search in Nested 1 is Nested of 2 which means parent2.parent1

        $city = Location::query()
            ->join('locations as parent2', 'locations.parent_id', '=', 'parent2.id') // Join with the parent table
            ->join('locations as parent1', 'parent2.parent_id', '=', 'parent1.id') // Join with the grandparent table
            ->select(
                'locations.*',
            )
            // Searching
            ->where(function ($query) use (
                $filterArrayNameEn,
                $filterArrayNameBn,
                $filterArrayCode,
                $parent2filterArrayNameEn,
                $parent2filterArrayNameBn,
                $parent2filterArrayCode,
                $parent1filterArrayNameEn,
                $parent1filterArrayNameBn,
                $parent1filterArrayCode,
            ) {

                $query->where($filterArrayNameEn)
                    ->orWhere($filterArrayNameBn)
                    ->orWhere($filterArrayCode) // City Search

                    ->orWhereHas('parent', function ($query) use (
                        $parent2filterArrayNameEn,
                        $parent2filterArrayNameBn,
                        $parent2filterArrayCode,
                        $parent1filterArrayNameEn,
                        $parent1filterArrayNameBn,
                        $parent1filterArrayCode,

                    ) {
                        $query->where($parent2filterArrayNameEn)
                            ->orWhere($parent2filterArrayNameBn)
                            ->orWhere($parent2filterArrayCode) // District Search

                            ->orWhereHas('parent', function ($query) use ($parent1filterArrayNameEn, $parent1filterArrayNameBn, $parent1filterArrayCode) {
                                $query->where($parent1filterArrayNameEn)
                                    ->orWhere($parent1filterArrayNameBn)
                                    ->orWhere($parent1filterArrayCode); // Division Search
                            });
                    });
            })
            //End Searching

            ->whereIn('locations.type', [$this->city, $this->thana])

            // Filtering
            ->when($location_type, function ($query, $location_type) {
                return $query->where('locations.location_type', $location_type);
            })
            ->when($district_id, function ($query, $district_id) {
                return $query->where('parent2.id', $district_id);
            })
            ->when($division_id, function ($query, $division_id) {
                return $query->where('parent1.id', $division_id);
            })
            // End Filtering

            // Ordering
            ->orderBy($sortBy, $orderBy)
            ->with('parent.parent', 'locationType')
            ->get()
        ;
        // Ordering

        return $city;
    }


    public function cityReport(Request $request)
    {
        $items = $this->getCityList($request);

        $data = ['items' => $items];

        $pdf = LaravelMpdf::loadView('reports.city', $data, [],
            [
                'mode' => 'utf-8',
                'format' => 'A4-P',
                'title' => 'উপজেলা/সিটি কর্পোরেশন/জেলা পৌরসভা তালিকা',
                'orientation' => 'L',
                'default_font_size' => 10,
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 10,
                'margin_bottom' => 10,
                'margin_header' => 10,
                'margin_footer' => 10,
            ]);


        $fileName = 'উপজেলা_সিটি_কর্পোরেশন_জেলা_পৌরসভা_তালিকা_' . now()->timestamp . '_'. auth()->id() . '.pdf';

        $pdfPath = public_path("/pdf/$fileName");

        $pdf->save($pdfPath);

        return $this->sendResponse(['url' => asset("/pdf/$fileName")]);
    }


    public function getUnionList($request)
    {

        //Filter
        $location_type = $request->query('location_type');
        $division_id = $request->query('division_id');
        $district_id = $request->query('district_id');

        $city_id = $request->query('city_id');
        $district_pouro_id = $request->query('district_pouro_id');
        $upazila_id = $request->query('upazila_id');
        //Filter

        // Retrieve the query parameters
        $searchText = $request->query('searchText');
        $sortBy = $request->query('sortBy') ?? 'name_en';
        $orderBy = $request->query('orderBy') ?? 'asc';

        $filterArrayNameEn = [];
        $filterArrayNameBn = [];
        $filterArrayCode = [];

        $parent3filterArrayNameEn = [];
        $parent3filterArrayNameBn = [];
        $parent3filterArrayCode = [];

        $parent2filterArrayNameEn = [];
        $parent2filterArrayNameBn = [];
        $parent2filterArrayCode = [];

        $parent1filterArrayNameEn = [];
        $parent1filterArrayNameBn = [];
        $parent1filterArrayCode = [];

        if ($searchText) {

            /// Union/Thana/Pouro
            $filterArrayNameEn[] = ['locations.name_en', 'LIKE', '%' . $searchText . '%'];
            $filterArrayNameBn[] = ['locations.name_bn', 'LIKE', '%' . $searchText . '%'];
            $filterArrayCode[]   = ['locations.code', 'LIKE', '%' . $searchText . '%'];

            /// Upazila/City/District Pouroshava
            $parent3filterArrayNameEn[] = ['parent3.name_en', 'LIKE', '%' . $searchText . '%'];
            $parent3filterArrayNameBn[] = ['parent3.name_bn', 'LIKE', '%' . $searchText . '%'];
            $parent3filterArrayCode[]   = ['parent3.code', 'LIKE', '%' . $searchText . '%'];
            /// District
            $parent2filterArrayNameEn[] = ['parent2.name_en', 'LIKE', '%' . $searchText . '%'];
            $parent2filterArrayNameBn[] = ['parent2.name_bn', 'LIKE', '%' . $searchText . '%'];
            $parent2filterArrayCode[]   = ['parent2.code', 'LIKE', '%' . $searchText . '%'];

            /// Division
            $parent1filterArrayNameEn[] = ['parent1.name_en', 'LIKE', '%' . $searchText . '%'];
            $parent1filterArrayNameBn[] = ['parent1.name_bn', 'LIKE', '%' . $searchText . '%'];
            $parent1filterArrayCode[]   = ['parent1.code', 'LIKE', '%' . $searchText . '%'];
        }

        //
        // this is a 3 Level Search/Sorting
        // so this will start from name which is at level 3
        // then parent.name which is at level 2
        // then parent.parent.name which is at level 1
        //

        // Level 3
        if ($sortBy == 'name_en') {
            $sortBy = 'name_en';
        }
        // Level 2
        if ($sortBy == 'parent.name_en') {
            $sortBy = 'parent3.name_en';
        }
        // Level 2
        if ($sortBy == 'parent.parent.name_en') {
            $sortBy = 'parent2.name_en';
        }
        // Level 1
        if ($sortBy == 'parent.parent.parent.name_en') {
            $sortBy = 'parent1.name_en';
        }

        ///
        // parent4
        // parent3
        // parent2
        // parent1
        /// JOIN and Search in Nested 1 is Nested of 2 which means parent2.parent1

        $union = Location::query()
            ->join('locations as parent3', 'locations.parent_id', '=', 'parent3.id') // Join with the parent table
            ->join('locations as parent2', 'parent3.parent_id', '=', 'parent2.id') // Join with the parent table
            ->join('locations as parent1', 'parent2.parent_id', '=', 'parent1.id') // Join with the grandparent table
            ->select(
                'locations.*',
            )

            //searching
            ->where(function ($query) use (
                $filterArrayNameEn,
                $filterArrayNameBn,
                $filterArrayCode,
                $parent3filterArrayNameEn,
                $parent3filterArrayNameBn,
                $parent3filterArrayCode,
                $parent2filterArrayNameEn,
                $parent2filterArrayNameBn,
                $parent2filterArrayCode,
                $parent1filterArrayNameEn,
                $parent1filterArrayNameBn,
                $parent1filterArrayCode
            ) {

                $query->where($filterArrayNameEn)
                    ->orWhere($filterArrayNameBn)
                    ->orWhere($filterArrayCode) // Union Level Search

                    ->orWhereHas('parent', function ($query) use (
                        $parent3filterArrayNameEn,
                        $parent3filterArrayNameBn,
                        $parent3filterArrayCode,
                        $parent2filterArrayNameEn,
                        $parent2filterArrayNameBn,
                        $parent2filterArrayCode,
                        $parent1filterArrayNameEn,
                        $parent1filterArrayNameBn,
                        $parent1filterArrayCode
                    ) {
                        $query->where($parent3filterArrayNameEn)
                            ->orWhere($parent3filterArrayNameBn)
                            ->orWhere($parent3filterArrayCode) // City Level Search

                            ->orWhereHas('parent', function ($query) use (
                                $parent2filterArrayNameEn,
                                $parent2filterArrayNameBn,
                                $parent2filterArrayCode,
                                $parent1filterArrayNameEn,
                                $parent1filterArrayNameBn,
                                $parent1filterArrayCode
                            ) {
                                $query->where($parent2filterArrayNameEn)
                                    ->orWhere($parent2filterArrayNameBn)
                                    ->orWhere($parent2filterArrayCode) // District Level Search

                                    ->orWhereHas('parent', function ($query) use ($parent1filterArrayNameEn, $parent1filterArrayNameBn, $parent1filterArrayCode) {
                                        $query->where($parent1filterArrayNameEn)
                                            ->orWhere($parent1filterArrayNameBn)
                                            ->orWhere($parent1filterArrayCode); // Division Level Search
                                    });
                            });
                    });
            })

            ->whereIn('locations.type', [$this->pouro, $this->thana, $this->union])

            ->when($city_id, function ($query, $city_id) {
                return $query->where('parent3.id', $city_id);
            })
            ->when($upazila_id, function ($query, $upazila_id) {
                return $query->where('parent3.id', $upazila_id);
            })

            ->when($district_id, function ($query, $district_id) {
                return $query->where('parent2.id', $district_id);
            })
            ->when($division_id, function ($query, $division_id) {
                return $query->where('parent1.id', $division_id);
            })

            // End Filtering

            ->orderBy($sortBy, $orderBy)
            ->with('parent.parent.parent', 'locationType')
            ->get();

        return $union;
    }



    public function unionReport(Request $request)
    {
        $items = $this->getUnionList($request);

        $data = ['items' => $items];

        $pdf = LaravelMpdf::loadView('reports.union', $data, [],
            [
                'mode' => 'utf-8',
                'format' => 'A4-P',
                'title' => 'থানা/ইউনিয়ন/পৌরসভা তালিকা',
                'orientation' => 'L',
                'default_font_size' => 10,
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 10,
                'margin_bottom' => 10,
                'margin_header' => 10,
                'margin_footer' => 10,
            ]);


        $fileName = 'থানা_ইউনিয়ন_পৌরসভা_তালিকা_' . now()->timestamp . '_'. auth()->id() . '.pdf';

        $pdfPath = public_path("/pdf/$fileName");

        $pdf->save($pdfPath);

        return $this->sendResponse(['url' => asset("/pdf/$fileName")]);
    }




}
