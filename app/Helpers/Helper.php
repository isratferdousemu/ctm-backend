<?php
namespace App\Helpers;

use App\Http\Traits\MessageTrait;
use Cache;
use Illuminate\Support\Str;

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



}
