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
    protected $fillable = [];


    public function monitor(): BelongsTo
    {
        return $this->belongsTo(Monitor::class);
    }
    public function monitor_log_entries(): HasMany{
        return $this->hasMany(MonitorLogEntries::class);
    }
}
