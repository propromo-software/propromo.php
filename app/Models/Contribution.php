<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contribution extends Model
{
    protected $fillable = [
        'commit_url',
        'message_headline',
        'message_body',
        'additions',
        'deletions',
        'changed_files',
        'committed_date',
        'author_id',
    ];

    protected $casts = [
        'committed_date' => 'datetime',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }
}
