<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileImportProgress extends Model
{
    use HasFactory;

    protected $table = 'file_import_progress';

    protected $fillable = [
        'file_path',
        'type',
        'total_rows',
        'processed_rows',
        'status',
        'started_at',
        'completed_at',
        'error_message',
    ];

    protected $casts = [
        'total_rows' => 'integer',
        'processed_rows' => 'integer',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];
}