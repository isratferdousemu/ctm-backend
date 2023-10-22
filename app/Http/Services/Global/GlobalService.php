<?php

namespace App\Http\Services\Global;

use App\Models\AllowanceProgram;

class GlobalService
{

    public function getPrograms()
    {
        return AllowanceProgram::with('lookup')->get();
    }
}
