<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
class MonitorLogs extends Model
{
    use HasFactory;

    protected $fillable = [
        'monitor_id',
        'status',
        'summary'
    ];

    // Each log belongs to a Monitor.
    public function monitor(): BelongsTo
    {
        return $this->belongsTo(Monitor::class)->onDelete('cascade');
    }

    // Each log can have multiple log entries.
    public function monitorLogEntries(): HasMany
    {
        return $this->hasMany(MonitorLogEntries::class)->onDelete('cascade');
    }
}
