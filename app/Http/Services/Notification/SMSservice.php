<?php

namespace App\Http\Services\Notification;

use Illuminate\Support\Facades\Http;
use Infobip\Api\SmsApi;
use Infobip\Configuration;
use Infobip\ApiException;
use Infobip\Model\SmsAdvancedTextualRequest;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsSendingSpeedLimit;
use Infobip\Model\SmsTextualMessage;
class SMSservice
{
    // send limit 1000 sms per minute
    private $api;



    public function sendSmsBulkBd($mobile, $message)
    {
        $url = "bulksmsbd.net/api/smsapi";

        $data = [
            'api_key' => "xp143oLW8GJtKk3ggwxW",
            'type' => "text",
            'message' => $message,
            'number' => $mobile,
            'senderid' => "8809617617434",
        ];

        $response = Http::contentType('application/json')
            ->post($url, $data);

        return $response->json();
    }


    //bulksmsbd, Infobip
    public function sendSms($mobile, $message)
    {
        $gateway = strtolower(env('SMS_GATEWAY', 'Infobip'));

        if ($gateway == 'infobip') {
            return $this->sendSmsInfobip($mobile, $message);
        }

        if ($gateway == 'bulksmsbd') {
            return $this->sendSmsBulkBd($mobile, $message);
        }
    }



    public function sendSmsInfobip($mobile, $message)
    {

        $configuration = new Configuration(
            host: 'xrdqwg.api.infobip.com',
            apiKey: '62dbca44587176113a2b2e96273f156a-6e09a3d9-eada-4e7d-8eba-03a27f00aeff'
        );
        $sendSmsApi = new SmsApi(config: $configuration);
        // check mobile number is valid or not has 88 or not if not add 88
        if (substr($mobile, 0, 2) != '88') {
            $mobile = '88' . $mobile;
        }



        $message = new SmsTextualMessage(
            destinations: [
                new SmsDestination(to: $mobile)
            ],
            from: 'InfoSMS',
            text: $message
        );

        // infobip sms sending speed limit
        $speedLimit = new SmsSendingSpeedLimit(
            amount: 1000,
            timeUnit: 'MINUTE'
        );

        $request = new SmsAdvancedTextualRequest(messages: [$message], bulkId: null, sendingSpeedLimit: $speedLimit);

        try {
            $smsResponse = $sendSmsApi->sendSmsMessage($request);
            $data['bulkId'] = $smsResponse->getBulkId() . PHP_EOL;
            // $data['bulkId'] = $smsResponse->getBulkId();
            $data['messages'] = $smsResponse->getMessages();
            $msg=[];
            foreach ($smsResponse->getMessages() ?? [] as $message) {
                $msg[]= sprintf('Message ID: %s, status: %s, Discriminator: %s ', $message->getMessageId(), $message->getStatus()?->getName(),$message->getTo()) . PHP_EOL;
            }
            $data['messageId'] = (!empty($data['messages'])) ? current($data['messages'])->getMessageId() : null;
            return $msg;
        } catch (ApiException $apiException) {
            $code['getCode']=$apiException->getCode();
            $code['getResponseHeaders']=$apiException->getResponseHeaders();
            $code['getResponseBody']=$apiException->getResponseBody();
            $code['getResponseObject']=$apiException->getResponseObject();
            throw New \Exception(json_encode($code));
            // throw New \Exception($apiException->getMessage());
        }
    }
}
