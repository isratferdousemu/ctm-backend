<?php

namespace App\Http\Services\Bank;

use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BankService
{
    // store bank details
    public function storeBank(Request $request)
    {
        $bank = new Bank;
        $bank->bank_name = $request->bank_name;
        $bank->bank_slug = Str::slug($request->bank_name);
        $bank->save();
        return $bank;
    }

}
