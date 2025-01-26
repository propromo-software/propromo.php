<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Release extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_draft',
        'is_latest',
        'is_prerelease',
        'url',
        'created_at',
        'updated_at',
        'repository_id',
        'monitor_id'
    ];

    protected $casts = [
        'is_draft' => 'boolean',
        'is_latest' => 'boolean',
        'is_prerelease' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function monitor(): BelongsTo
    {
        return $this->belongsTo(Monitor::class);
    }

    public function repository(): BelongsTo
    {
        return $this->belongsTo(Repository::class);
    }

    public function tag(): HasOne
    {
        return $this->hasOne(Tag::class);
    }
}
