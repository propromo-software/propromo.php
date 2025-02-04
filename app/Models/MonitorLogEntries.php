<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\BelongsToRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonitorLogEntries extends Model
{
    use HasFactory;
    public $table = 'monitor_logs';

    protected $fillable = [];

    public function monitor_log(): BelongsTo
    {
        return $this->belongsTo(MonitorLogs::class);
    }
}
