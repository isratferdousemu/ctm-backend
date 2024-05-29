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
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $currentYear = Carbon::now()->year;
        $thirtyDaysAgo = Carbon::now()->subDays(30);

        // dd( $thirtyDaysAgo);
        // Initialize the query
        $query = Location::where('type', 'division');
        if ($request->status == 'location') {
            if ($startDate && $endDate) {
                $query->with(['grievances' => function ($query) use ($startDate, $endDate, $status) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }]);
            } else {
                // If start date and end date are not provided, filter by the current year
                $query->with(['grievances' => function ($query) use ($thirtyDaysAgo, $status) {
                    $query->where('created_at', '>=', $thirtyDaysAgo);

                }]);
            }

            // Adding withCount to get total_grievance_approved and total_grievance_canceled
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

            // dd($query->get());
        } else {
            // If start date and end date are provided, filter by the specified date range
            if ($startDate && $endDate) {
                $query->withCount(['grievances' => function ($query) use ($startDate, $endDate, $status) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                    // $query->where('status', $status);
                }]);
            } else {
                // If start date and end date are not provided, filter by the current year
                $query->withCount(['grievances' => function ($query) use ($currentYear, $status) {
                    $query->whereYear('created_at', $currentYear);
                    // $query->where('status', $status);
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

        // Execute the query
        $programs = $query->get();

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