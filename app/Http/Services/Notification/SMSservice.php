<?php

namespace App\Http\Services\Notification;

use Infobip\Api\SmsApi;
use Infobip\Configuration;
use Infobip\ApiException;
use Infobip\Model\SmsAdvancedTextualRequest;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsTextualMessage;
class SMSservice
{
    public function sendSms($mobile, $message)
    {
        $configuration = new Configuration(
            host: 'xrdqwg.api.infobip.com',
            apiKey: '62dbca44587176113a2b2e96273f156a-6e09a3d9-eada-4e7d-8eba-03a27f00aeff'
        );
        $sendSmsApi = new SmsApi(config: $configuration);

        $message = new SmsTextualMessage(
            destinations: [
                new SmsDestination(to: $mobile)
            ],
            from: 'InfoSMS',
            text: $message
        );

        $request = new SmsAdvancedTextualRequest(messages: [$message]);

        // try {
            $smsResponse = $sendSmsApi->sendSmsMessage($request);
            return $smsResponse;
        // } catch (ApiException $apiException) {
        //     throw New \Exception($apiException->getMessage());
        // }
    }
}
