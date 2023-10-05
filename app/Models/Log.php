<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;
    protected $fillable = [
        'import_type',
        'total_records',
        'successful_records',
        'failed_records',
        'failure_reason'
    ];
}
