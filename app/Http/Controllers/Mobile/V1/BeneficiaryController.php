<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mobile\Beneficiary\BeneficiaryExitRequest;
use App\Http\Requests\Mobile\Beneficiary\BeneficiaryLocationShiftingRequest;
use App\Http\Requests\Mobile\Beneficiary\BeneficiaryShiftingRequest;
use App\Http\Requests\Mobile\Beneficiary\DeleteBeneficiaryRequest;
use App\Http\Requests\Mobile\Beneficiary\ReplaceBeneficiaryRequest;
use App\Http\Requests\Mobile\Beneficiary\SearchBeneficiaryRequest;
use App\Http\Requests\Mobile\Beneficiary\UpdateBeneficiaryRequest;
use App\Http\Resources\Mobile\Beneficiary\BeneficiaryDropDownResource;
use App\Http\Resources\Mobile\Beneficiary\BeneficiaryExitResource;
use App\Http\Resources\Mobile\Beneficiary\BeneficiaryLocationShiftingResource;
use App\Http\Resources\Mobile\Beneficiary\BeneficiaryReplaceResource;
use App\Http\Resources\Mobile\Beneficiary\BeneficiaryResource;
use App\Http\Resources\Mobile\Beneficiary\BeneficiaryShiftingResource;
use App\Http\Services\Mobile\Beneficiary\BeneficiaryService;
use App\Http\Traits\MessageTrait;
use Illuminate\Http\Request;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf;
use Mpdf\MpdfException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

/**
 *
 */
class BeneficiaryController extends Controller
{
    use MessageTrait;

    /**
     * @var BeneficiaryService
     */
    private BeneficiaryService $beneficiaryService;

    /**
     * @param BeneficiaryService $beneficiaryService
     */
    public function __construct(BeneficiaryService $beneficiaryService)
    {
        $this->beneficiaryService = $beneficiaryService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserLocation(): \Illuminate\Http\JsonResponse
    {
        $uerLocation = $this->beneficiaryService->getUserLocation();
        return response()->json([
            'data' => $uerLocation,
            'success' => true,
            'message' => $this->fetchSuccessMessage,
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * Display a listing of the resource.
     */
    public function list(SearchBeneficiaryRequest $request): \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        try {
            $beneficiaryList = $this->beneficiaryService->list($request);
//            return response()->json($beneficiaryList);
            return BeneficiaryResource::collection($beneficiaryList)->additional([
                'success' => true,
                'message' => $this->fetchSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    public function listDropDown(Request $request)
    {
        try {
            $beneficiaryList = $this->beneficiaryService->listDropDown($request);
//            return response()->json($beneficiaryList);
            return BeneficiaryDropDownResource::collection($beneficiaryList)->additional([
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
     * @return \Illuminate\Http\JsonResponse|BeneficiaryResource
     */
    public function show($id): \Illuminate\Http\JsonResponse|BeneficiaryResource
    {
        try {
            $beneficiary = $this->beneficiaryService->detail($id);
            if ($beneficiary) {
                return BeneficiaryResource::make($beneficiary)->additional([
                    'success' => true,
                    'message' => $this->fetchSuccessMessage,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $this->notFoundMessage,
                ], ResponseAlias::HTTP_OK);
            }

        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse|BeneficiaryResource
     */
    public function get($id): \Illuminate\Http\JsonResponse|BeneficiaryResource
    {
        try {
            $beneficiary = $this->beneficiaryService->get($id);
            if ($beneficiary) {
                return BeneficiaryResource::make($beneficiary)->additional([
                    'success' => true,
                    'message' => $this->fetchSuccessMessage,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $this->notFoundMessage,
                ], ResponseAlias::HTTP_OK);
            }

        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    /**
     * @param $beneficiary_id
     * @return \Illuminate\Http\JsonResponse|BeneficiaryResource
     */
    public function getByBeneficiaryId($beneficiary_id): \Illuminate\Http\JsonResponse|BeneficiaryResource
    {
        try {
            $beneficiary = $this->beneficiaryService->getByBeneficiaryId($beneficiary_id);
            if ($beneficiary) {
                return BeneficiaryResource::make($beneficiary)->additional([
                    'success' => true,
                    'message' => $this->fetchSuccessMessage,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $this->notFoundMessage,
                ], ResponseAlias::HTTP_OK);
            }

        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse|BeneficiaryResource
     */
    public function edit($id): \Illuminate\Http\JsonResponse|BeneficiaryResource
    {
        try {
            $beneficiary = $this->beneficiaryService->detail($id);
            return BeneficiaryResource::make($beneficiary)->additional([
                'success' => true,
                'message' => $this->fetchSuccessMessage,
            ]);

        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    /**
     * @param UpdateBeneficiaryRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|BeneficiaryResource
     */
    public function update(UpdateBeneficiaryRequest $request, $id): \Illuminate\Http\JsonResponse|BeneficiaryResource
    {
        try {
            $beneficiary = $this->beneficiaryService->update($request, $id);
            return BeneficiaryResource::make($beneficiary)->additional([
                'success' => true,
                'message' => $this->updateSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    /**
     * @param DeleteBeneficiaryRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(DeleteBeneficiaryRequest $request, $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->beneficiaryService->delete($request, $id);
            return response()->json([
                'success' => true,
                'message' => $this->deleteSuccessMessage,
            ], ResponseAlias::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    public function deletedList(SearchBeneficiaryRequest $request): \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        try {
            $beneficiaryList = $this->beneficiaryService->deletedList($request);
//            return response()->json($beneficiaryList);
            return BeneficiaryResource::collection($beneficiaryList)->additional([
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
     * @return BeneficiaryResource|\Illuminate\Http\JsonResponse
     */
    public function restore($id): \Illuminate\Http\JsonResponse|BeneficiaryResource
    {
        try {
            $this->beneficiaryService->restore($id);
            return response()->json([
                'success' => true,
                'message' => $this->updateSuccessMessage,
            ], ResponseAlias::HTTP_OK);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    public function restoreInactive($id): \Illuminate\Http\JsonResponse|BeneficiaryResource
    {
        try {
            $this->beneficiaryService->restoreInactive($id);
            return response()->json([
                'success' => true,
                'message' => $this->updateSuccessMessage,
            ], ResponseAlias::HTTP_OK);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    public function restoreExit($id): \Illuminate\Http\JsonResponse|BeneficiaryResource
    {
        try {
            $this->beneficiaryService->restoreExit($id);
            return response()->json([
                'success' => true,
                'message' => $this->updateSuccessMessage,
            ], ResponseAlias::HTTP_OK);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    public function restoreReplace($id): \Illuminate\Http\JsonResponse|BeneficiaryResource
    {
        try {
            $this->beneficiaryService->restoreReplace($id);
            return response()->json([
                'success' => true,
                'message' => $this->updateSuccessMessage,
            ], ResponseAlias::HTTP_OK);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    /**
     * @param SearchBeneficiaryRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getListForReplace(SearchBeneficiaryRequest $request): \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        try {
            $beneficiaryList = $this->beneficiaryService->getListForReplace($request);
//            return response()->json($beneficiaryList);
            return BeneficiaryResource::collection($beneficiaryList)->additional([
                'success' => true,
                'message' => $this->fetchSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    /**
     * @param ReplaceBeneficiaryRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|BeneficiaryResource
     */
    public function replaceSave(ReplaceBeneficiaryRequest $request, $id): \Illuminate\Http\JsonResponse|BeneficiaryResource
    {
        try {
            $beneficiary = $this->beneficiaryService->replaceSave($request, $id);
            return BeneficiaryResource::make($beneficiary)->additional([
                'success' => true,
                'message' => $this->updateSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    /**
     * @param SearchBeneficiaryRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function replaceList(SearchBeneficiaryRequest $request): \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        try {
            $beneficiaryList = $this->beneficiaryService->replaceList($request);
//            return response()->json($beneficiaryList);
            return BeneficiaryReplaceResource::collection($beneficiaryList)->additional([
                'success' => true,
                'message' => $this->fetchSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    /**
     * @param BeneficiaryExitRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function exitSave(BeneficiaryExitRequest $request): \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        try {
            $this->beneficiaryService->exitSave($request);
            return response()->json([
                'success' => true,
                'message' => $this->deleteSuccessMessage,
            ], ResponseAlias::HTTP_OK);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    /**
     * @param SearchBeneficiaryRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function exitList(SearchBeneficiaryRequest $request): \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        try {
            $beneficiaryList = $this->beneficiaryService->exitList($request);
//            return response()->json($beneficiaryList);
            return BeneficiaryExitResource::collection($beneficiaryList)->additional([
                'success' => true,
                'message' => $this->fetchSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    /**
     * @param BeneficiaryShiftingRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function shiftingSave(BeneficiaryShiftingRequest $request): \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        try {
            $this->beneficiaryService->shiftingSave($request);
            return response()->json([
                'success' => true,
                'message' => $this->updateSuccessMessage,
            ], ResponseAlias::HTTP_OK);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    /**
     * @param SearchBeneficiaryRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function shiftingList(SearchBeneficiaryRequest $request): \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        try {
            $beneficiaryList = $this->beneficiaryService->shiftingList($request);
//            return response()->json($beneficiaryList);
            return BeneficiaryShiftingResource::collection($beneficiaryList)->additional([
                'success' => true,
                'message' => $this->fetchSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    /**
     * @param BeneficiaryLocationShiftingRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function locationShiftingSave(BeneficiaryLocationShiftingRequest $request): \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        try {
            $this->beneficiaryService->locationShiftingSave($request);
            return response()->json([
                'success' => true,
                'message' => $this->updateSuccessMessage,
            ], ResponseAlias::HTTP_OK);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    /**
     * @param SearchBeneficiaryRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function locationShiftingList(SearchBeneficiaryRequest $request): \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        try {
            $beneficiaryList = $this->beneficiaryService->locationShiftingList($request);
//            return response()->json($beneficiaryList);
            return BeneficiaryLocationShiftingResource::collection($beneficiaryList)->additional([
                'success' => true,
                'message' => $this->fetchSuccessMessage,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError($th->getMessage(), [], 500);
        }
    }

    /**
     * @param SearchBeneficiaryRequest $request
     * @return ResponseAlias
     * @throws MpdfException
     */
    public function getBeneficiaryListPdf(SearchBeneficiaryRequest $request): ResponseAlias
    {
        $beneficiaries = $this->beneficiaryService->list($request, true);
        $user = auth()->user()->load('assign_location.parent.parent.parent.parent');
        $generated_by = $user->full_name;
        $assign_location = '';
        if ($user->assign_location) {
            $assign_location .= ', ' . (app()->isLocale('bn') ? $user->assign_location?->name_bn : $user->assign_location?->name_en);
            if ($user->assign_location?->parent) {
                $assign_location .= ', ' . (app()->isLocale('bn') ? $user->assign_location?->parent?->name_bn : $user->assign_location?->parent?->name_en);
                if ($user->assign_location?->parent?->parent) {
                    $assign_location .= ', ' . (app()->isLocale('bn') ? $user->assign_location?->parent?->parent?->name_bn : $user->assign_location?->parent?->parent?->name_en);
//                    if ($user->assign_location?->parent?->parent?->parent) {
//                        $assign_location .= ', ' . $user->assign_location?->parent?->parent?->parent?->name_bn;
//                    }
                }
            }
        }
        $data = ['beneficiaries' => $beneficiaries, 'generated_by' => $generated_by, 'assign_location' => $assign_location];
        $pdf = LaravelMpdf::loadView('reports.beneficiary.beneficiary_list', $data, [],
            [
                'mode' => 'utf-8',
                'format' => 'A4-L',
                'title' => __("beneficiary_list.page_title"),
                'orientation' => 'L',
                'default_font_size' => 10,
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 10,
                'margin_bottom' => 25,
                'margin_header' => 10,
                'margin_footer' => 5,
            ]);

        return \Illuminate\Support\Facades\Response::stream(
            function () use ($pdf) {
                echo $pdf->output();
            },
            200,
            [
                'Content-Type' => 'application/pdf;charset=utf-8',
                'Content-Disposition' => 'inline; filename="preview.pdf"',
            ]);

//        $fileName = 'উপকারভোগীর_তালিকা_' . now()->timestamp . '_' . auth()->id() . '.pdf';
//        $pdfPath = public_path("/pdf/$fileName");
//        $pdf->save($pdfPath);
//        return $this->sendResponse(['url' => asset("/pdf/$fileName")]);
    }

    /**
     * @param SearchBeneficiaryRequest $request
     * @return ResponseAlias
     * @throws MpdfException
     */
    public function getBeneficiaryExitListPdf(SearchBeneficiaryRequest $request): ResponseAlias
    {
        $beneficiaries = $this->beneficiaryService->exitList($request, true);
        $user = auth()->user()->load('assign_location.parent.parent.parent.parent');
        $generated_by = $user->full_name;
        $assign_location = '';
        if ($user->assign_location) {
            $assign_location .= ', ' . (app()->isLocale('bn') ? $user->assign_location?->name_bn : $user->assign_location?->name_en);
            if ($user->assign_location?->parent) {
                $assign_location .= ', ' . (app()->isLocale('bn') ? $user->assign_location?->parent?->name_bn : $user->assign_location?->parent?->name_en);
                if ($user->assign_location?->parent?->parent) {
                    $assign_location .= ', ' . (app()->isLocale('bn') ? $user->assign_location?->parent?->parent?->name_bn : $user->assign_location?->parent?->parent?->name_en);
//                    if ($user->assign_location?->parent?->parent?->parent) {
//                        $assign_location .= ', ' . $user->assign_location?->parent?->parent?->parent?->name_bn;
//                    }
                }
            }
        }
        $data = ['beneficiaries' => $beneficiaries, 'generated_by' => $generated_by, 'assign_location' => $assign_location];
        $pdf = LaravelMpdf::loadView('reports.beneficiary.beneficiary_exit_list', $data, [],
            [
                'mode' => 'utf-8',
                'format' => 'A4-L',
                'title' => __("beneficiary_exit.page_title"),
                'orientation' => 'L',
                'default_font_size' => 10,
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 10,
                'margin_bottom' => 10,
                'margin_header' => 10,
                'margin_footer' => 5,
            ]);

        return \Illuminate\Support\Facades\Response::stream(
            function () use ($pdf) {
                echo $pdf->output();
            },
            200,
            [
                'Content-Type' => 'application/pdf;charset=utf-8',
                'Content-Disposition' => 'inline; filename="preview.pdf"',
            ]);

//        $fileName = 'উপকারভোগীর_প্রস্থান_তালিকা_' . now()->timestamp . '_' . auth()->id() . '.pdf';
//        $pdfPath = public_path("/pdf/$fileName");
//        $pdf->save($pdfPath);
//        return $this->sendResponse(['url' => asset("/pdf/$fileName")]);
    }

    /**
     * @param SearchBeneficiaryRequest $request
     * @return ResponseAlias
     * @throws MpdfException
     */
    public function getBeneficiaryReplaceListPdf(SearchBeneficiaryRequest $request): ResponseAlias
    {
        $beneficiaries = $this->beneficiaryService->replaceList($request, true);
        $user = auth()->user()->load('assign_location.parent.parent.parent.parent');
        $generated_by = $user->full_name;
        $assign_location = '';
        if ($user->assign_location) {
            $assign_location .= ', ' . (app()->isLocale('bn') ? $user->assign_location?->name_bn : $user->assign_location?->name_en);
            if ($user->assign_location?->parent) {
                $assign_location .= ', ' . (app()->isLocale('bn') ? $user->assign_location?->parent?->name_bn : $user->assign_location?->parent?->name_en);
                if ($user->assign_location?->parent?->parent) {
                    $assign_location .= ', ' . (app()->isLocale('bn') ? $user->assign_location?->parent?->parent?->name_bn : $user->assign_location?->parent?->parent?->name_en);
//                    if ($user->assign_location?->parent?->parent?->parent) {
//                        $assign_location .= ', ' . $user->assign_location?->parent?->parent?->parent?->name_bn;
//                    }
                }
            }
        }
        $data = ['beneficiaries' => $beneficiaries, 'generated_by' => $generated_by, 'assign_location' => $assign_location];
        $pdf = LaravelMpdf::loadView('reports.beneficiary.beneficiary_replace_list', $data, [],
            [
                'mode' => 'utf-8',
                'format' => 'A4-L',
                'title' => __("beneficiary_replace.page_title"),
                'orientation' => 'L',
                'default_font_size' => 10,
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 10,
                'margin_bottom' => 10,
                'margin_header' => 10,
                'margin_footer' => 5,
            ]);

        return \Illuminate\Support\Facades\Response::stream(
            function () use ($pdf) {
                echo $pdf->output();
            },
            200,
            [
                'Content-Type' => 'application/pdf;charset=utf-8',
                'Content-Disposition' => 'inline; filename="preview.pdf"',
            ]);

//        $fileName = 'উপকারভোগী_পরিবর্তন_তালিকা_' . now()->timestamp . '_' . auth()->id() . '.pdf';
//        $pdfPath = public_path("/pdf/$fileName");
//        $pdf->save($pdfPath);
//        return $this->sendResponse(['url' => asset("/pdf/$fileName")]);
    }

    /**
     * @param SearchBeneficiaryRequest $request
     * @return ResponseAlias
     * @throws MpdfException
     */
    public function getBeneficiaryShiftingListPdf(SearchBeneficiaryRequest $request): ResponseAlias
    {
        $beneficiaries = $this->beneficiaryService->shiftingList($request, true);
        $user = auth()->user()->load('assign_location.parent.parent.parent.parent');
        $generated_by = $user->full_name;
        $assign_location = '';
        if ($user->assign_location) {
            $assign_location .= ', ' . (app()->isLocale('bn') ? $user->assign_location?->name_bn : $user->assign_location?->name_en);
            if ($user->assign_location?->parent) {
                $assign_location .= ', ' . (app()->isLocale('bn') ? $user->assign_location?->parent?->name_bn : $user->assign_location?->parent?->name_en);
                if ($user->assign_location?->parent?->parent) {
                    $assign_location .= ', ' . (app()->isLocale('bn') ? $user->assign_location?->parent?->parent?->name_bn : $user->assign_location?->parent?->parent?->name_en);
//                    if ($user->assign_location?->parent?->parent?->parent) {
//                        $assign_location .= ', ' . $user->assign_location?->parent?->parent?->parent?->name_bn;
//                    }
                }
            }
        }
        $data = ['beneficiaries' => $beneficiaries, 'generated_by' => $generated_by, 'assign_location' => $assign_location];
        $pdf = LaravelMpdf::loadView('reports.beneficiary.beneficiary_shifting_list', $data, [],
            [
                'mode' => 'utf-8',
                'format' => 'A4-L',
                'title' => __("beneficiary_shifting.page_title"),
                'orientation' => 'L',
                'default_font_size' => 10,
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 10,
                'margin_bottom' => 10,
                'margin_header' => 10,
                'margin_footer' => 5,
            ]);

        return \Illuminate\Support\Facades\Response::stream(
            function () use ($pdf) {
                echo $pdf->output();
            },
            200,
            [
                'Content-Type' => 'application/pdf;charset=utf-8',
                'Content-Disposition' => 'inline; filename="preview.pdf"',
            ]);

//        $fileName = 'উপকারভোগী_স্থানান্তর_তালিকা_' . now()->timestamp . '_' . auth()->id() . '.pdf';
//        $pdfPath = public_path("/pdf/$fileName");
//        $pdf->save($pdfPath);
//        return $this->sendResponse(['url' => asset("/pdf/$fileName")]);
    }

}
