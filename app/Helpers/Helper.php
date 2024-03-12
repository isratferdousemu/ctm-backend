<?php
namespace App\Helpers;

use App\Http\Traits\MessageTrait;
use Cache;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
use Stevebauman\Location\Facades\Location;

class Helper{
    use MessageTrait;

    public static function GeneratePassword()
    {
        $password = Str::random(8); // Generate a random 10-character password

        // Add special characters to the password
        $specialCharacters = '!@#$%^&*()';
        $randomSpecialCharacter = $specialCharacters[rand(0, strlen($specialCharacters) - 1)];
        $password .= $randomSpecialCharacter;
        // $password = Str::random(8);
        return $password;
    }
    public static function GenerateFourDigitNumber()
    {
        $fourDigitNumber = random_int(1000, 9999);
        return $fourDigitNumber;
    }
    public static function FinancialYear(){
        $currentDate = now();
        $startOfFinancialYear = $currentDate->month >= 4 ? $currentDate->year : $currentDate->year - 1;
        $endOfFinancialYear = $startOfFinancialYear + 1;

        return "{$startOfFinancialYear}-{$endOfFinancialYear}";
    }

    public static function generateSalt(){

        $salt = Str::random(20) . Str::random(10);
        return $salt;

    }


    public static function englishToBangla($number) {

        $banglaDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        $englishDigits = range(0, 9);

        $banglaNumber = str_replace($englishDigits, $banglaDigits, $number);

        return $banglaNumber;
    }
      public static function urlToBase64($imageData)
    {
    $imageType = getimagesizefromstring($imageData)['mime'];

    // Convert the image data to base64 format
     $imageBase64= 'data:' . $imageType . ';base64,' . base64_encode($imageData);

        return   $imageBase64;
    }

    public static function clientIp(){
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $ip){
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                        return $ip;
                    }
                }
            }
        }
        return request()->ip();
    }

    public static function BrowserIpInfo(){
        $agent = new Agent();
        $agentinfo = $agent->browser() . " in " . $agent->platform();
        if ($agentinfo == ' in ') {
            $agentinfo =  request()->header('User-Agent');
        }
        $ip = self::clientIp();
        $currentUserInfo = Location::get($ip);

        if($currentUserInfo == true){
            $data = [
                'userAgent' => $agentinfo,
                'browser' => $agent->browser(),
                'platform' => $agent->platform(),
                'deviceType' => $agent->deviceType(),
                'ipAddress' => $ip,
                'countryName' => $currentUserInfo->countryName ?? null,
                'countryCode' => $currentUserInfo->countryCode ?? null,
                'regionName' => $currentUserInfo->regionName ?? null,
                'cityName' => $currentUserInfo->cityName ?? null,
                'latitude' => $currentUserInfo->latitude ?? null,
                'longitude' => $currentUserInfo->longitude ?? null,
                'timezone' => $currentUserInfo->timezone ?? null,
            ];
//            $jsonString = json_encode($data);
        }
        else {
            $data = [
                'userAgent' => $agentinfo,
                'browser' => $agent->browser(),
                'platform' => $agent->platform(),
                'deviceType' => $agent->deviceType(),
                'ipAddress' => $ip,
                'countryName' => $currentUserInfo->countryName ?? null,
                'countryCode' => $currentUserInfo->countryCode ?? null,
                'regionName' => $currentUserInfo->regionName ?? null,
                'cityName' => $currentUserInfo->cityName ?? null,
                'latitude' => $currentUserInfo->latitude ?? null,
                'longitude' => $currentUserInfo->longitude ?? null,
                'timezone' => $currentUserInfo->timezone ?? null,
            ];
//            $jsonString = json_encode($data);
        }
        return $data;
    }
}
