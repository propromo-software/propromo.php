<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    protected $fillable = [
        'id',
        'name',
        'email',
        'avatar_url',
    ];

    public function tags(): HasMany
    {
        return $this->hasMany(Tag::class);
    }

    public function releases(): HasMany
    {
        return $this->hasMany(Release::class);
    }

    public function contributions(): HasMany
    {
        return $this->hasMany(Contribution::class);
    }
}
