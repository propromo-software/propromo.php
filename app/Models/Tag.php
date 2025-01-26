<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tag extends Model
{
    protected $fillable = [
        'name',
        'additions',
        'deletions',
        'changed_files',
        'authored_at',
        'author_id',
        'release_id'
    ];

    protected $casts = [
        'authored_at' => 'datetime'
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function release(): BelongsTo
    {
        return $this->belongsTo(Release::class);
    }
}
