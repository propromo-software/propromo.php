<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Contribution extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'commit_url',
        'message_headline',
        'message_body',
        'additions',
        'deletions',
        'changed_files',
        'committed_date'
    ];

    protected $casts = [
        'committed_date' => 'datetime'
    ];

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'contribution_author');
    }

    protected static function booted()
    {
        static::creating(function ($contribution) {
            if (!$contribution->id) {
                if (preg_match('/\/commit\/([a-f0-9]{40})$/', $contribution->commit_url, $matches)) {
                    $contribution->id = $matches[1];
                }
            }
        });
    }
}
