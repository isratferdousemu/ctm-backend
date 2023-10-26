<?php

namespace App\Http\Services\Admin\Application;

use App\Exceptions\AuthBasicErrorException;
use App\Http\Traits\ApplicationTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Response;

class ApplicationService
{
    use ApplicationTrait;
    public function onlineApplicationVerifyCard(Request $request)
    {
        $fakeNID = '12345678';
        $fakeDOB = '87654321';
        if ($request->verification_type == $this->verificationTypeNID) {
            if($request->verification_number != $fakeNID){
            // throw new \Exception('NID is not valid');
            // throw new \Exception('NID is not valid');
             throw new AuthBasicErrorException(
                HttpResponse::HTTP_UNPROCESSABLE_ENTITY,
                'not_valid',
                'NID is not valid',
            );
            }else{
                return 'NID is valid';
            }
        }
        if ($request->verification_type == $this->verificationTypeDOB) {
            if($request->verification_number != $fakeDOB){
                // throw new \Exception('NID is not valid');
                throw new AuthBasicErrorException(
                    HttpResponse::HTTP_UNPROCESSABLE_ENTITY,
                    'not_valid',
                    'DOB is not valid',
                );
                }else{
                    return 'DOB is valid';

                }
        }

    }

    public function onlineApplicationVerifyCardDIS(Request $request)
    {
        $fakeDIS = '12345678';

        if($request->dis_no != $fakeDIS){
            throw new AuthBasicErrorException(
            HttpResponse::HTTP_UNPROCESSABLE_ENTITY,
            'not_valid',
            'DIS is not valid',
        );
        }else{
            return 'DIS is valid';
        }


    }

}
