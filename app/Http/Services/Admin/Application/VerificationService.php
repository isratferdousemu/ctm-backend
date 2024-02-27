<?php

namespace App\Http\Services\Admin\Application;

use App\Exceptions\AuthBasicErrorException;
use App\Models\AllowanceProgram;
use App\Models\FinancialYear;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Http;

class VerificationService
{

    public const BASE_URL = "https://mis.bhata.gov.bd";

    public const NID_API = "/test-nid-data";


    public function callVerificationApi($data)
    {
        $data['dob'] = Carbon::parse($data['dob'])->format('d-m-Y');

        $response = Http::contentType('application/json')
            ->post(self::BASE_URL . self::NID_API, $data);


        if ($response->failed()) {
            throw new AuthBasicErrorException(
                HttpResponse::HTTP_UNPROCESSABLE_ENTITY,
                'invalid_nid',
                "NID or Date of birth is invalid",
            );
        }

        $nidInfo = $response->json('success.data');
        $nidInfo['age'] = $this->calculateAge($data);

        return $nidInfo;
    }





    public function callNomineeVerificationApi($data)
    {
        $data['dob'] = Carbon::parse($data['dob'])->format('d-m-Y');

        $response = Http::contentType('application/json')
            ->post(self::BASE_URL . self::NID_API, $data);


        if ($response->failed()) {
            throw new AuthBasicErrorException(
                HttpResponse::HTTP_UNPROCESSABLE_ENTITY,
                'invalid_nominee_nid',
                "Nominee NID or Date of birth is invalid",
            );
        }

        $nidInfo = $response->json('success.data');

        return $nidInfo;
    }



    public function calculateAge($data)
    {
        $finYear = FinancialYear::whereStatus(1)->first();

        if (!$finYear) {
            throw new AuthBasicErrorException(
                HttpResponse::HTTP_UNPROCESSABLE_ENTITY,
                'internal_error',
                'No financial year found',
            );
        }


        return Carbon::parse($data['dob'])->diff($finYear->end_date)->y;
    }












    public function formatData()
    {

    }






}
