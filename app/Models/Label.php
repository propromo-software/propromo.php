<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Label
 *
 * @property int $id
 * @property int $task_id
 * @property string|null $url
 * @property string|null $name
 * @property string|null $color
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $description
 * @property bool|null $is_default
 * @property-read \App\Models\Task $task
 * @method static \Illuminate\Database\Eloquent\Builder|Label newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Label newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Label query()
 * @method static \Illuminate\Database\Eloquent\Builder|Label whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Label whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Label whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Label whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Label whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Label whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Label whereTaskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Label whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Label whereUrl($value)
 * @mixin \Eloquent
 */
class Label extends Model
{
    use HasFactory;

    protected $fillable = [
        "task_id",
        "url",
        "name",
        "color",
        "created_at",
        "updated_at",
        "description",
        "is_default"
    ];


    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
