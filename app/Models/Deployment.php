<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Deployment extends Model
{
    protected $fillable = [
        'created_at',
        'updated_at',
        'description',
        'log_url',
        'environment_url',
        'state',
        'monitor_id'
    ];

    public function monitor(): BelongsTo
    {
        return $this->belongsTo(Monitor::class);
    }
}
