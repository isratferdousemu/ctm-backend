<?php
namespace App\Helpers;

use App\Http\Traits\MessageTrait;
use Cache;
use Illuminate\Support\Str;

class Helper{
    use MessageTrait;

    public static function GeneratePassword()
    {
        $password = Str::random(10); // Generate a random 10-character password

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

    // public static function generateOtpCode($user, $time)
    // {
    //     Cache::forget(self::userLoginOtpPrefix . $user->id);
    //     //generate code
    //     $code =  mt_rand(100000, 999999);
    //     //put them in cache
    //     Cache::put(self::userLoginOtpPrefix . $user->id, $code, now()->addMinutes($time));
    //     //return generated code
    //     return $code;
    // }
}
