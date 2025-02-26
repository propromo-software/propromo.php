<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\BelongsToRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonitorLogEntries extends Model
{
    use HasFactory;

    protected $table = 'monitor_log_entries';

    protected $fillable = [
        'monitor_log_id',
        'message',
        'level',
        'context'
    ];

    public function monitorLog(): BelongsTo
    {
        return $this->belongsTo(MonitorLogs::class);
    }
}
