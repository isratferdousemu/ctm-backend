<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Bank;
use App\Models\Location;
use App\Models\Variable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\MobileOperator;
use App\Models\AllowanceProgram;
use App\Http\Traits\MessageTrait;
use App\Http\Controllers\Controller;
use App\Http\Services\Global\GlobalService;
use App\Models\PayrollPaymentProcessorArea;
use App\Http\Resources\Admin\CommonResource;
use App\Http\Resources\Admin\PMTScore\VariableResource;
use App\Http\Resources\Admin\Systemconfig\Allowance\AllowanceResource;

class GlobalController extends Controller
{
    use MessageTrait;
    private $globalService;

    public function __construct(GlobalService $globalService)
    {
        $this->globalService = $globalService;
    }

    /**
     * @OA\Get(
     *     path="/global/program",
     *      operationId="getAllProgram",
     *     tags={"GLOBAL"},
     *      summary="get all program",
     *      description="get all program",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful Insert operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *
     *          )
     * )
     */
    public function getAllProgram()
    {
        $data = AllowanceProgram::where('is_active', 1)->with('lookup', 'addtionalfield.additional_field_value')->get();
        return AllowanceResource::collection($data)->additional([
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ]);
    }
    /**
     * @OA\Get(
     *     path="/global/mobile-operator",
     *      operationId="getAllMobileOperator",
     *     tags={"GLOBAL"},
     *      summary="get all mobile operator",
     *      description="get all mobile operator",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful Insert operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *
     *          )
     * )
     */
    public function getAllMobileOperator()
    {
        $data = MobileOperator::get();
        return $data;
    }
    /**
     * @OA\Get(
     *     path="/global/pmt",
     *      operationId="getAllPMTVariableWithSub",
     *     tags={"GLOBAL"},
     *      summary="get all PMT variable with sub-variable",
     *      description="get all PMT variable with sub-variables",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful Insert operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *
     *          )
     * )
     */
    public function getAllPMTVariableWithSub()
    {
        $data = Variable::whereParentId(null)->with('children')->get();
        return VariableResource::collection($data)->additional([
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ]);
    }


    public function dropdownList(Request $request)
    {
        $data = $this->globalService->getdropdownList($request);
        return handleResponse($data, null);
    }
     public function coverageArea($location_type,$sub_location,$location_id)

    {
        if($location_type == 3){
        // $area=PayrollPaymentProcessorArea::where('thana_id',$location_id)
      
        // ->with('payment_processor.bank')
        // ->get();
        $area = PayrollPaymentProcessorArea::where('thana_id', $location_id)
        ->whereHas('payment_processor', function ($query) {
            $query->where('processor_type', 'bank');
        })
        ->with(['payment_processor' => function ($query) {
            $query->with('bank');
        }])
        ->get();
        $mfs=PayrollPaymentProcessorArea::where('thana_id', $location_id)
        ->whereHas('payment_processor', function ($query) {
            $query->where('processor_type', 'mfs');
        })
        ->with(['payment_processor' => function ($query) {
            $query->with('bank');
        }])
        ->get();

        }
          if($location_type == 1){
        // $area=PayrollPaymentProcessorArea::where('district_pourashava_id',$location_id)
        // ->with('payment_processor.bank')
        // ->get();
         $area = PayrollPaymentProcessorArea::where('district_pourashava_id', $location_id)
        ->whereHas('payment_processor', function ($query) {
            $query->where('processor_type', 'bank');
        })
        ->with(['payment_processor' => function ($query) {
            $query->with('bank');
        }])
        ->get();
        $mfs=PayrollPaymentProcessorArea::where('district_pourashava_id', $location_id)
        ->whereHas('payment_processor', function ($query) {
            $query->where('processor_type', 'mfs');
        })
        ->with(['payment_processor' => function ($query) {
            $query->with('bank');
        }])
        ->get();

        }
          if($location_type == 2){
            if($sub_location==1){
                //   $area=PayrollPaymentProcessorArea::where('pourashava_id',$location_id)
                // ->with('payment_processor.bank')
                // ->get();
                $area = PayrollPaymentProcessorArea::where('pourashava_id', $location_id)
                ->whereHas('payment_processor', function ($query) {
                    $query->where('processor_type', 'bank');
                })
                ->with(['payment_processor' => function ($query) {
                    $query->with('bank');
                }])
                ->get();
                $mfs=PayrollPaymentProcessorArea::where('pourashava_id', $location_id)
                ->whereHas('payment_processor', function ($query) {
                    $query->where('processor_type', 'mfs');
                })
                ->with(['payment_processor' => function ($query) {
                    $query->with('bank');
                }])
                ->get();


            }
              if($sub_location==2){
                //   $area=PayrollPaymentProcessorArea::where('union_id',$location_id)
                // ->with('payment_processor.bank')
                // ->get();
                $area = PayrollPaymentProcessorArea::where('union_id', $location_id)
                ->whereHas('payment_processor', function ($query) {
                    $query->where('processor_type', 'bank');
                })
                ->with(['payment_processor' => function ($query) {
                    $query->with('bank');
                }])
                ->get();
                $mfs=PayrollPaymentProcessorArea::where('union_id', $location_id)
                ->whereHas('payment_processor', function ($query) {
                    $query->where('processor_type', 'mfs');
                })
                ->with(['payment_processor' => function ($query) {
                    $query->with('bank');
                }])
                ->get();

            }
      

        }
      
       
           return([
            'success' => true,
            'message' => $this->fetchSuccessMessage,
            'bank'=> $area,
             'mfs'=> $mfs
        ]);
        
    }
    
}
