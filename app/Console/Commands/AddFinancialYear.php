<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Helpers\Helper;
use App\Models\FinancialYear;
use App\Models\FinancialYear1;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


class AddFinancialYear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'financial-year:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a new financial year';

    /**
     * Execute the console command.
     */
    public function handle()
    {
    $financialYear = $this->calculateFinancialYear();
   $financialYearArray = explode('-', $financialYear);
        $seventhMonth = 6;
        $sixthMonth = 6;
        $startDate = Carbon::create($financialYearArray[0], $seventhMonth, 1);
        $lastDate = Carbon::create($financialYearArray[1], $sixthMonth , 1)->subDay();
    
   

    $financialYearData = [
        'financial_year' => $financialYear,
        'start_date' => $startDate,
        'end_date' => $lastDate,
        'status' => 1
      
    ];

    $existingFinancialYear = FinancialYear1::where('financial_year', $financialYear)->first();
    if ($existingFinancialYear) {
        $this->info('Financial year already exists.');
        return;
    }
    
    FinancialYear1::where('status', 1)->update(['status' => 0]);

    FinancialYear1::create($financialYearData);

    $this->info("Financial year inserted successfully.");
    }
      private function calculateFinancialYear()
    {
        $currentDate = now();
        $startOfFinancialYear = $currentDate->year;
        $endOfFinancialYear = $startOfFinancialYear + 1;

        return "{$startOfFinancialYear}-{$endOfFinancialYear}";
    }
    }

