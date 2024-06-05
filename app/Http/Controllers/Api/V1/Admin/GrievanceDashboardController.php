<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\Admin\Application\ApplicationService;
use App\Http\Traits\MessageTrait;
use App\Models\AllowanceProgram;
use App\Models\Application;
use App\Models\Grievance;
use App\Models\Location;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class GrievanceDashboardController extends Controller
{
    use MessageTrait;

    /**
     * @var ApplicationService
     */
    private ApplicationService $applicationService;

    /**
     * @param ApplicationService $applicationService
     */
    public function __construct(ApplicationService $applicationService)
    {
        $this->applicationService = $applicationService;
    }

    public function programStatusWisetotalNumberOfGrievance(Request $request)
    {
        $status = $request->status;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $currentYear = Carbon::now()->year;

        // Initialize the query
        $query = AllowanceProgram::where('system_status', 1);

        // If start date and end date are provided, filter by the specified date range
        if ($startDate && $endDate) {
            $query->withCount(['grievances' => function ($query) use ($startDate, $endDate, $status) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
                $query->where('status', $status);
            }]);
        } else {
            // If start date and end date are not provided, filter by the current year
            $query->withCount(['grievances' => function ($query) use ($currentYear, $status) {
                $query->whereYear('created_at', $currentYear);
                $query->where('status', $status);
            }]);
        }

        // Execute the query
        $programs = $query->get();

        return response()->json([
            'data' => $programs,
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ], ResponseAlias::HTTP_OK);

    }
    public function locationWisetotalNumberOfGrievance(Request $request)
    {
        $status = $request->status;
        $type = $request->type;
        $breadcrumb = $request->breadcrumb;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $currentYear = Carbon::now()->year;
        $thirtyDaysAgo = Carbon::now()->subDays(30);
        
        $parentId = $request->get('parent_id', null);

        // $type = $request->get('type', 'division');
        if ($breadcrumb == 'breadcrumb' && $type == 'division') {
            // $type = 'division';
            $query = Location::where('type', $type);

        } elseif ($breadcrumb == 'breadcrumb' && $type == 'district') {
          $query = Location::where('type', $type);

        } elseif ($breadcrumb == 'breadcrumb' && $type == 'thana') {
           $query = Location::where('type', $type);
        } else {
            $type = $request->get('type', 'division');
            $query = Location::where('parent_id', $parentId)->where('type', $type);

            if ($parentId) {
             $query->where('parent_id', $parentId);
             }

            // return  $type;
        }
        
        // // $type = $request->get('type', 'division'); // default to division if type is not provided
        // $parentId = $request->get('parent_id', null); // get parent_id if provided

        // $query = Location::where('parent_id', $parentId)->where('type', $type);
        // // return $query->get();
        // if ($parentId) {
        //     $query->where('parent_id', $parentId);
        // }

        if ($request->status == 'location') {

            if ($startDate && $endDate) {
                $query->with(['grievances' => function ($query) use ($startDate, $endDate, $status) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }]);
            } else {
                // If start date and end date are not provided, filter by the current year
                if ($type == 'division') {
                    $query->with(['grievances' => function ($query) use ($thirtyDaysAgo, $status) {
                        $query->where('created_at', '>=', $thirtyDaysAgo);

                    }]);
                } elseif ($type == 'district') {
                    $query->with(['districtGrievances' => function ($query) use ($thirtyDaysAgo, $status) {
                        $query->where('created_at', '>=', $thirtyDaysAgo);

                    }]);

                } elseif ($type == 'thana') {
                    $query->with(['thanasGrievances' => function ($query) use ($thirtyDaysAgo, $status) {
                        $query->where('created_at', '>=', $thirtyDaysAgo);

                    }]);

                }
            }

            if ($type == 'division') {
                $query->withCount([
                    'grievances as total_grievance_approved' => function ($query) {
                        $query->where('status', 2);
                    },
                    'grievances as total_grievance_new' => function ($query) {
                        $query->where('status', 2);
                    },
                    'grievances as total_grievance_canceled' => function ($query) {
                        $query->where('status', 3);
                    },
                    'grievances as total_grievance_pending' => function ($query) {
                        $query->where('status', 0);
                    },

                ]);

            } elseif ($type == 'district') {
                $query->withCount([
                    'districtGrievances as total_grievance_approved' => function ($query) {
                        $query->where('status', 2);
                    },
                    'districtGrievances as total_grievance_new' => function ($query) {
                        $query->where('status', 2);
                    },
                    'districtGrievances as total_grievance_canceled' => function ($query) {
                        $query->where('status', 3);
                    },
                    'districtGrievances as total_grievance_pending' => function ($query) {
                        $query->where('status', 0);
                    },

                ]);

            } elseif ($type == 'thana') {
                $query->withCount([
                    'thanasGrievances as total_grievance_approved' => function ($query) {
                        $query->where('status', 2);
                    },
                    'thanasGrievances as total_grievance_new' => function ($query) {
                        $query->where('status', 2);
                    },
                    'thanasGrievances as total_grievance_canceled' => function ($query) {
                        $query->where('status', 3);
                    },
                    'thanasGrievances as total_grievance_pending' => function ($query) {
                        $query->where('status', 0);
                    },

                ]);

                // Adding withCount to get total_grievance_approved and total_grievance_canceled
                // $query->withCount([
                //     'grievances as total_grievance_approved' => function ($query) {
                //         $query->where('status', 2);
                //     },
                //     'grievances as total_grievance_new' => function ($query) {
                //         $query->where('status', 2);
                //     },
                //     'grievances as total_grievance_canceled' => function ($query) {
                //         $query->where('status', 3);
                //     },
                //     'grievances as total_grievance_pending' => function ($query) {
                //         $query->where('status', 0);
                //     },

                // ]);
            }
        } else {
            // If start date and end date are provided, filter by the specified date range
            if ($startDate && $endDate) {
                $query->withCount(['grievances' => function ($query) use ($startDate, $endDate, $status) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }]);
            } else {
                // If start date and end date are not provided, filter by the current year
                $query->withCount(['grievances' => function ($query) use ($currentYear, $status) {
                    $query->whereYear('created_at', $currentYear);
                }]);
            }

        }

        // Execute the query
        $programs = $query->get();

        return response()->json([
            'data' => $programs,
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ], ResponseAlias::HTTP_OK);
        // }
    }

    public function statusWisetotalNumberOfGrievance(Request $request)
    {
        $status = $request->status;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $currentYear = Carbon::now()->year;

        // Initialize the query
        $query = AllowanceProgram::where('system_status', 1);

        // If start date and end date are provided, filter by the specified date range
        if ($startDate && $endDate) {
            $query->withCount(['grievances' => function ($query) use ($startDate, $endDate, $status) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
                $query->where('status', $status);
            }]);
        } else {
            // If start date and end date are not provided, filter by the current year
            $query->withCount(['grievances' => function ($query) use ($currentYear, $status) {
                $query->whereYear('created_at', $currentYear);
                $query->where('status', $status);
            }]);
        }

        // Execute the query
        $programs = $query->get();

        return response()->json([
            'data' => $programs,
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ], ResponseAlias::HTTP_OK);

    }

    public function totalNumberOfdGrievance(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $currentYear = Carbon::now()->year;

        // Initialize the query
        $query = AllowanceProgram::where('system_status', 1);

        // If start date and end date are provided, filter by the specified date range
        if ($startDate && $endDate) {
            $query->withCount(['grievances' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }]);

        } else {
            // If start date and end date are not provided, filter by the current year
            $query->withCount(['grievances' => function ($query) use ($currentYear) {
                $query->whereYear('created_at', $currentYear);

            }]);
        }
        //  return $query->get();

        // Execute the query
        $programs = $query->get();

        // return  $programs;

        return response()->json([
            'data' => $programs,
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ], ResponseAlias::HTTP_OK);
    }
    public function numberReceivedOfGrievance(Request $request)
    {

        $totalCount = Grievance::get()->count();
        return response()->json([
            'data' => $totalCount,
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ], ResponseAlias::HTTP_OK);
    }
    public function numberOfSolvedGrievance(Request $request)
    {

        if ($request->status == 2) {
            $query = Grievance::where('status', 2);
        }

        // Execute the query
        $totalCount = $query->get()->count();
        return response()->json([
            'data' => $totalCount,
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ], ResponseAlias::HTTP_OK);
    }
    public function numberOfCanceledGrievance(Request $request)
    {

        if ($request->status == 3) {
            $query = Grievance::where('status', 3);
        }

        // Execute the query
        $totalCount = $query->get()->count();
        return response()->json([
            'data' => $totalCount,
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ], ResponseAlias::HTTP_OK);
    }

    public function numberOfPendingdGrievance(Request $request)
    {

        if ($request->status == 0) {
            $query = Grievance::where('status', 0);
        }

        // Execute the query
        $totalCount = $query->get()->count();
        return response()->json([
            'data' => $totalCount,
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ], ResponseAlias::HTTP_OK);
    }
}