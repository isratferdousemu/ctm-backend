<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Beneficiary\StoreCommitteeRequest;
use App\Http\Requests\Admin\Beneficiary\UpdateCommitteeRequest;
use App\Http\Resources\Admin\Beneficiary\Committee\CommitteeResource;
use App\Http\Services\Admin\Beneficiary\CommitteeService;
use App\Http\Traits\MessageTrait;
use App\Models\Committee;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

/**
 * CommitteeController
 */
class CommitteeController extends Controller
{
    use MessageTrait;

    /**
     * @var CommitteeService
     */
    private CommitteeService $committeeService;

    /**
     * @param CommitteeService $committeeService
     */
    public function __construct(CommitteeService $committeeService)
    {
        $this->committeeService = $committeeService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|AnonymousResourceCollection
     */
    public function list(Request $request): \Illuminate\Http\JsonResponse|AnonymousResourceCollection
    {
        try {
            $committeeList = $this->committeeService->list($request);
            return CommitteeResource::collection($committeeList)->additional([
                'success' => true,
                'message' => $this->fetchSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    /**
     * @param StoreCommitteeRequest $request
     * @return \Illuminate\Http\JsonResponse|CommitteeResource
     */
    public function add(StoreCommitteeRequest $request): \Illuminate\Http\JsonResponse|CommitteeResource
    {
        try {
            $committee = $this->committeeService->save($request);
            activity("Committee")
                ->causedBy(auth()->user())
                ->performedOn($committee)
                ->log('Committee Created !');
            return CommitteeResource::make($committee)->additional([
                'success' => true,
                'message' => $this->insertSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse|CommitteeResource
     */
    public function show($id): \Illuminate\Http\JsonResponse|CommitteeResource
    {
        try {
            $committee = $this->committeeService->detail($id);
            return CommitteeResource::make($committee)->additional([
                'success' => true,
                'message' => $this->fetchSuccessMessage,
            ]);

        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse|CommitteeResource
     */
    public function edit($id): \Illuminate\Http\JsonResponse|CommitteeResource
    {
        try {
            $committee = $this->committeeService->detail($id);
            return CommitteeResource::make($committee)->additional([
                'success' => true,
                'message' => $this->fetchSuccessMessage,
            ]);

        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }


    public function update(UpdateCommitteeRequest $request, $id): \Illuminate\Http\JsonResponse|CommitteeResource
    {
        try {
            $committee = $this->committeeService->update($request, $id);
            activity("Committee")
                ->causedBy(auth()->user())
                ->performedOn($committee)
                ->log('Committee Updated !');
            return CommitteeResource::make($committee)->additional([
                'success' => true,
                'message' => $this->updateSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    /**
     * @param Committee $committee
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->committeeService->delete($id);
            activity("Committee")
                ->causedBy(auth()->user())
                ->log('Committee Deleted!!');
            return response()->json([
                'success' => true,
                'message' => $this->deleteSuccessMessage,
            ], ResponseAlias::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), [], 500);
        }
    }
}
