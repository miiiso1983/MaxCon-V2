<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportExecution extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'user_id',
        'parameters',
        'status',
        'result_data',
        'execution_time',
        'row_count',
        'file_path',
        'export_format',
        'error_message',
        'started_at',
        'completed_at'
    ];

    protected $casts = [
        'parameters' => 'array',
        'result_data' => 'array',
        'execution_time' => 'decimal:2',
        'row_count' => 'integer',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Execution Status
    const STATUS_PENDING = 'pending';
    const STATUS_RUNNING = 'running';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';

    // Export Formats
    const FORMAT_PDF = 'pdf';
    const FORMAT_EXCEL = 'excel';
    const FORMAT_CSV = 'csv';
    const FORMAT_HTML = 'html';
    const FORMAT_JSON = 'json';

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isFailed()
    {
        return $this->status === self::STATUS_FAILED;
    }

    public function isRunning()
    {
        return $this->status === self::STATUS_RUNNING;
    }

    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'في الانتظار',
            self::STATUS_RUNNING => 'قيد التنفيذ',
            self::STATUS_COMPLETED => 'مكتمل',
            self::STATUS_FAILED => 'فشل',
            self::STATUS_CANCELLED => 'ملغي',
        ];
    }

    public static function getFormats()
    {
        return [
            self::FORMAT_PDF => 'PDF',
            self::FORMAT_EXCEL => 'Excel',
            self::FORMAT_CSV => 'CSV',
            self::FORMAT_HTML => 'HTML',
            self::FORMAT_JSON => 'JSON',
        ];
    }
}
