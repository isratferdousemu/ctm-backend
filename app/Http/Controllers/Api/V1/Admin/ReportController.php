<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Resources\Admin\Geographic\DistrictResource;
use App\Http\Traits\LocationTrait;
use App\Http\Traits\MessageTrait;
use App\Http\Traits\UserTrait;
use App\Models\Location;
use Illuminate\Http\Request;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf;

class ReportController
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

        return view('reports.division_report', $data);

        $pdf = LaravelMpdf::loadView('reports.division_report', $data, [],
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


        $fileName = 'আবেদনের_তালিকা_' . now()->timestamp . '_'. auth()->id() . '.pdf';

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

            if ($searchText != null) {
                $page = 1;
            }
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

        return view('reports.division_report', $data);

        $pdf = LaravelMpdf::loadView('reports.division_report', $data, [],
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


        $fileName = 'আবেদনের_তালিকা_' . now()->timestamp . '_'. auth()->id() . '.pdf';

        $pdfPath = public_path("/pdf/$fileName");

        $pdf->save($pdfPath);

        return $this->sendResponse(['url' => asset("/pdf/$fileName")]);
    }




}
