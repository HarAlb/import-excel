<?php

namespace App\Http\Api\V1\Files;

use App\Http\Api\Base\Constants\FileUploadConstants;
use App\Http\Api\Base\Controller\ApiController;
use App\Http\Api\V1\Files\Jobs\ImportJob;
use App\Http\Api\V1\Files\Requests\StoreRequest;
use App\Models\ImportedExcelData;
use App\Models\UploadedFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FilesController extends ApiController
{
    public function index(): JsonResponse
    {
        return $this->responseJson(UploadedFile::where('user_id', auth()->user()->id)->paginate());
    }

    public function store(StoreRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $fileName = uniqid() . '.' . $request->file->extension();
            $filePath = 'public/' . auth()->user()->id . '/' . FileUploadConstants::DISK_NAME . '/' . $fileName;
            Storage::put($filePath, file_get_contents($request->file->getRealPath()));
            $uploded = UploadedFile::create([
                'name' => $fileName,
                'size' => $request->file->getSize() / 1024,
                'user_id' => auth()->user()->id,
                'extension' => $request->file->extension()
            ]);

            $batch = Bus::batch(
                new ImportJob($filePath, $uploded)
            )->dispatch();

            DB::commit();

            return $this->responseJson([
                'success' => true,
                'message' => 'File uploaded',
                'file_id' => $uploded->id,
                'batch_id' => $batch->id
            ]);
        } catch (\Exception $exception) {
            Log::error($exception);
            DB::rollBack();
        }

        return $this->serverError($exception->getMessage());
    }

    public function insertedData(int $id): JsonResponse
    {
        $insertedData = ImportedExcelData::where('file_id', $id)->get();

        return $this->successResponse($insertedData->groupBy('date'));
    }
}

