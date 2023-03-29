<?php

namespace App\Http\Api\V1\Files\Imports;

use App\Events\AfterImportExcelFile;
use App\Models\ExcelData;
use App\Models\ImportedExcelData;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ExcelCollection implements ToCollection, WithChunkReading, ShouldQueue, WithCalculatedFormulas, WithHeadingRow
{
    private int $fileId;
    private int $userId;

    public function __construct()
    {
        //
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        $insert = [];
        $cr_data = date('Y-m-d H:i:s');

        foreach ($rows as $row) {
            if (!isset($row['name'])) {
                continue;
            }
            $insert[] = [
                'user_id' => $this->userId,
                'file_id' => $this->fileId,
                'line_id' => $row['id'],
                'name' => $row['name'] ?? '',
                'date' => date('Y-m-d', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($row['date'])),
                'created_at' => $cr_data,
                'updated_at' => $cr_data
            ];
        }
        ImportedExcelData::insert($insert);
        $client = new \Predis\Client();
        $client->set( $this->fileId, count($insert));

    }


    public function chunkSize(): int
    {
        return 1000;
    }

    /**
     * @param int $fileId
     */
    public function setFileId(int $fileId): void
    {
        $this->fileId = $fileId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }
}
