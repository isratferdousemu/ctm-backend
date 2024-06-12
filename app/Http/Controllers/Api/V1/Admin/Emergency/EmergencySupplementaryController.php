<?php

namespace App\Http\Controllers\Api\V1\Admin\Emergency;

use App\Http\Controllers\Controller;
use App\Models\EmergencyPayrollPaymentCycle;
use Illuminate\Http\Request;

class EmergencySupplementaryController extends Controller
{
    public function emergencySupplementaryPayrollData(Request $request){
        $supplementary = EmergencyPayrollPaymentCycle::with(['CycleDetails' => function($query) {
            $query->select(
                'emergency_cycle_id',
                \DB::raw('COUNT(*) as total'),
                \DB::raw('SUM(CASE WHEN status = "Failed" THEN 1 ELSE 0 END) as failed_count'),
                \DB::raw('SUM(CASE WHEN status = "Re-Submitted" THEN 1 ELSE 0 END) as resubmitted_count'),
                // \DB::raw('SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected_count')
            )->groupBy('emergency_cycle_id');
        }])->paginate(request('perPage'));

        return response()->json($supplementary);
    }
}
