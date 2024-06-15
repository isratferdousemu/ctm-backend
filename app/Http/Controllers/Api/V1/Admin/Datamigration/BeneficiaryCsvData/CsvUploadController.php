<?php

namespace App\Http\Controllers\Api\V1\Admin\Datamigration\BeneficiaryCsvData;

use App\Http\Controllers\Controller;
use App\Models\Beneficiary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class CsvUploadController extends Controller
{
    public function upload(Request $request){

//        ini_set('memory_limit', '512M');
//        ini_set('memory_limit', '-1');

        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,csv,xls|max:8120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid file upload.',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = Excel::toCollection(null,$request->file('file'));

        $uploadfileColumn =  $data->first()[0]->toArray();
        $currentbeneficiaryTableColumn = Schema::getColumnListing('beneficiaries');
        $diffcolumn = array_diff($uploadfileColumn,$currentbeneficiaryTableColumn);
        $rowCount = $data->first()->count();
        return response()->json([
            'status' =>'success',
            'message' => 'File uploaded successfully.',
            'data' => $data->first(),
            'rowCount' => $rowCount,
            'column_diff' => $diffcolumn,
        ], 200);
    }

    public function store(Request $request)
    {
        $this->uploadData($request);
    }

    public function uploadData($request)
    {
        $currentbeneficiaryTableColumn = Schema::getColumnListing('beneficiaries');
        $finalData = json_decode($request->input('final_data'), true);
//        dd($currentbeneficiaryTableColumn,$finalData[0]);

        $matchedData = [];
        foreach ($finalData[0] as $key => $value) {
            if (in_array($value, $currentbeneficiaryTableColumn)) {
                $matchedData[$key] = $value;
            }
        }

        $convertData = [];
        foreach ($finalData as $key => $value) {
            if ($key === 0) {
                $keys = $value;
                continue;
            }
            $record = [];
            foreach ($keys as $index => $columnName) {
                $record[$columnName] = $value[$index];
            }
            $convertData[] = $record;
        }


        $finalData = array_slice($finalData, 1);

        foreach ($convertData as $column) {
            $id = isset($column['id']) ? $column['id'] : null;
            $dataToSave = [];
            foreach ($column as $infoKey => $infoValue) {
                if (in_array($infoKey, $currentbeneficiaryTableColumn)) {
                    if (!empty($infoValue) && $infoValue !== '') {
                        // Format date fields if necessary
                        if (in_array($infoKey, ['date_of_birth', 'nominee_date_of_birth', 'application_date', 'approve_date'])) {
                            $formattedDate = date('Y-m-d', strtotime(str_replace('/', '-', $infoValue)));
                            $dataToSave[$infoKey] = $formattedDate ?? null;
                        } else {
                            $dataToSave[$infoKey] = $infoValue;
                        }
                    } else {
                        $dataToSave[$infoKey] = null;
                    }
                }
            }
            if ($id !== null) {
                Beneficiary::updateOrCreate(
                    ['id' => $id],
                    $dataToSave
                );
            } else {
                Beneficiary::create($dataToSave);
            }
        }

        return response()->json([
            'status' =>'success',
            'message' => 'File uploaded successfully.',
        ], 200);
    }
}
