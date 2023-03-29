<?php

namespace App\Http\Api\V1\Files\Jobs;

use App\Http\Api\V1\Files\Imports\ExcelCollection;
use App\Models\UploadedFile;
use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel as ExcelFacade;

class ImportJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $uploadedFile;
    private UploadedFile $insertedFile;

    /**
     * Create a new job instance.
     */
    public function __construct(string $uploadedFile, UploadedFile $insertedFile)
    {
        $this->uploadedFile = $uploadedFile;
        $this->insertedFile = $insertedFile;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $importer = new ExcelCollection();
        $importer->setFileId($this->insertedFile->id);
        $importer->setUserId($this->insertedFile->user_id);
        Log::info($this->batch()->id);
        ExcelFacade::import($importer, $this->uploadedFile);
    }
}
