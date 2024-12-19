<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Task
 *
 * @property-read \App\Models\Milestone|null $milestone
 * @method static \Illuminate\Database\Eloquent\Builder|Task newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Task newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Task query()
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereUpdatedAt($value)
 * @property int $milestone_id
 * @property bool $is_active
 * @property string $body_url
 * @property string $last_edited_at
 * @property string $closed_at
 * @property string $body
 * @property string $title
 * @property string $url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Assignee> $assignees
 * @property-read int|null $assignees_count
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereBodyUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereClosedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereLastEditedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereMilestoneId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereUrl($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Label> $labels
 * @property-read int|null $labels_count
 * @mixin \Eloquent
 */
class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        "milestone_id",
        'title',
        'url',
        'body_url',
        'closed_at',
        'last_edited_at',
        'body',
        'custom_repository_id',
        'is_active',
    ];

    public function milestone(): BelongsTo
    {
        return $this->belongsTo(Milestone::class);
    }

    public function assignees(): HasMany
    {
        return $this->hasMany(Assignee::class);
    }

    public function labels(): HasMany
    {
        return $this->hasMany(Label::class);
    }
}
