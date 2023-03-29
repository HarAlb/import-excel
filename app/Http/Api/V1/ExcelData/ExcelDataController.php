<?php

namespace App\Http\Api\V1\ExcelData;


use App\Http\Api\Base\Controller\ApiController;
use App\Models\ImportedExcelData;
use Illuminate\Http\JsonResponse;

class ExcelDataController extends ApiController
{
    public function index(): JsonResponse
    {
        return ImportedExcelData::groupBy('date');
    }
}
