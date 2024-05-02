<?php

namespace App\Http\Controllers\Client;

use App\Constants\ApiKey;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Beneficiary\AccountRequest;
use App\Http\Requests\Client\Beneficiary\GetListRequest;
use App\Http\Requests\Client\Beneficiary\NomineeRequest;
use App\Http\Resources\Admin\Beneficiary\BeneficiaryResource;
use App\Http\Services\Client\ApiService;
use App\Http\Services\Client\BeneficiaryService;
use App\Http\Traits\MessageTrait;
use App\Models\ApiDataReceive;
use App\Models\Application;
use App\Models\Beneficiary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class BeneficiaryController extends Controller
{
    use MessageTrait;

    public function __construct(public ApiService $apiService, public BeneficiaryService $beneficiaryService)
    {
    }


    /**
     * Get beneficiaries list
     *
     * Retrieves list of beneficiaries based on the provided request parameters.
     * @param GetListRequest $request
     * @return AnonymousResourceCollection
     * @throws \Throwable
     */
    public function getBeneficiariesList(GetListRequest $request)
    {
        $columns = $this->apiService->hasPermission($request, ApiKey::BENEFICIARIES_LIST);

        $this->apiService->validateColumnSearch($request, $columns);

        $beneficiaryList = $this->beneficiaryService->getList($request, $columns);

        return BeneficiaryResource::collection($beneficiaryList)->additional([
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ]);
    }


    /**
     * Get beneficiary by tracking id
     *
     * Fetch beneficiary details by beneficiary tracking id
     * @param Request $request
     * @param $beneficiary_id
     * @return BeneficiaryResource|JsonResponse
     * @throws \Throwable
     */
    public function getBeneficiaryById(Request $request, $beneficiary_id)
    {
        $request->validate([
            //Auth key
            'auth_key' => 'required',
            //Secret key
            'auth_secret' => 'required',
        ]);

        $columns = $this->apiService->hasPermission($request, ApiKey::BENEFICIARY_BY_BENEFICIARY_ID);

        $beneficiary = Beneficiary::with('program')
            ->where('beneficiary_id', $beneficiary_id)
            ->first();

        if ($beneficiary) {
            return BeneficiaryResource::make($beneficiary)->additional([
                'success' => true,
                'message' => $this->fetchSuccessMessage,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $this->notFoundMessage,
        ], 404);

    }


    /**
     * Update beneficiary nominee information
     *
     * Update beneficiary nominee information
     * @param NomineeRequest $request
     * @param $beneficiary_id
     * @return BeneficiaryResource|JsonResponse
     * @throws \Throwable
     */
    public function updateNomineeInfo(NomineeRequest $request, $beneficiary_id)
    {
        $columns = $this->apiService->hasPermission($request, ApiKey::BENEFICIARY_NOMINEE_UPDATE);

        $beneficiary = Beneficiary::where('beneficiary_id', $beneficiary_id)
            ->first();

        $beforeUpdate = $beneficiary->replicate();


        if ($beneficiary) {
            $beneficiary = $this->beneficiaryService->updateNominee($request, $beneficiary, $columns);

            Helper::activityLogUpdate($beneficiary, $beforeUpdate,'Beneficiary - External','Nominee Info Updated !');

            return BeneficiaryResource::make($beneficiary)->additional([
                'success' => true,
                'message' => $this->fetchSuccessMessage,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $this->notFoundMessage,
        ], 404);

    }


    /**
     * Update beneficiary account information
     *
     * Update beneficiary account information
     * @param AccountRequest $request
     * @param $beneficiary_id
     * @return BeneficiaryResource|JsonResponse
     * @throws \Throwable
     */
    public function updateAccountInfo(AccountRequest $request, $beneficiary_id)
    {
        $columns = $this->apiService->hasPermission($request, ApiKey::BENEFICIARY_ACCOUNT_UPDATE);

        $beneficiary = Beneficiary::where('beneficiary_id', $beneficiary_id)
            ->first();

        $beforeUpdate = $beneficiary->replicate();

        if ($beneficiary) {
            $beneficiary = $this->beneficiaryService->updateAccount($request, $beneficiary, $columns);

            Helper::activityLogUpdate($beneficiary, $beforeUpdate,'Beneficiary - External','Account Info Updated !');

            return BeneficiaryResource::make($beneficiary)->additional([
                'success' => true,
                'message' => $this->fetchSuccessMessage,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $this->notFoundMessage,
        ], 404);

    }




















}
