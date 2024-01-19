<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        "project_url",
        "project_identification",
        "user_id"
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
