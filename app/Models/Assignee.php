<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Assignee
 *
 * @property int $id
 * @property int $task_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Task $task
 * @method static \Illuminate\Database\Eloquent\Builder|Assignee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Assignee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Assignee query()
 * @method static \Illuminate\Database\Eloquent\Builder|Assignee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Assignee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Assignee whereTaskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Assignee whereUpdatedAt($value)
 * @property string|null $avatar_url
 * @property string|null $email
 * @property string|null $login
 * @property string|null $name
 * @property string|null $pronouns
 * @property string|null $url
 * @property string|null $website_url
 * @method static \Illuminate\Database\Eloquent\Builder|Assignee whereAvatarUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Assignee whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Assignee whereLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Assignee whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Assignee wherePronouns($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Assignee whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Assignee whereWebsiteUrl($value)
 * @mixin \Eloquent
 */
class Assignee extends Model
{
    use HasFactory;

    protected $fillable = [
        "task_id",
        "avatar_url",
        "email",
        "login",
        "name",
        "pronouns",
        "url",
        "website_url"
    ];


    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
