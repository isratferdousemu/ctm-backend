<?php
namespace App\Helpers;

use Illuminate\Support\Str;

class Helper{

    public static function GeneratePassword()
    {
        $password = Str::random(8);
        return $password;
    }
    public static function GenerateFourDigitNumber()
    {
        $fourDigitNumber = random_int(1000, 9999);
        return $fourDigitNumber;
    }
}
