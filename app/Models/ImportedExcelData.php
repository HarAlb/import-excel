<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportedExcelData extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'file_id',
        'name',
        'date',
        'line_id'
    ];
}
