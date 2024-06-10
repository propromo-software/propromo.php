<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Milestone
 *
 * @property int $id
 * @property string $name
 * @property float $progress
 * @property int $project_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Milestone $project
 * @method static \Illuminate\Database\Eloquent\Builder|Milestone newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Milestone newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Milestone query()
 * @method static \Illuminate\Database\Eloquent\Builder|Milestone whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Milestone whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Milestone whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Milestone whereProgress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Milestone whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Milestone whereUpdatedAt($value)
 * @property string $title
 * @property string $url
 * @property string $state
 * @property string $description
 * @method static \Illuminate\Database\Eloquent\Builder|Milestone whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Milestone whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Milestone whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Milestone whereUrl($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Task> $tasks
 * @property-read int|null $tasks_count
 * @property int $closed_issues_count
 * @property int $open_issues_count
 * @method static \Illuminate\Database\Eloquent\Builder|Milestone whereClosedIssuesCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Milestone whereOpenIssuesCount($value)
 * @property int $repository_id
 * @property-read \App\Models\Repository|null $repository
 * @method static \Illuminate\Database\Eloquent\Builder|Milestone whereRepositoryId($value)
 * @property int|null $open_issue_count
 * @property int|null $closed_issue_count
 * @method static \Illuminate\Database\Eloquent\Builder|Milestone whereClosedIssueCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Milestone whereOpenIssueCount($value)
 * @property string|null $due_on
 * @method static \Illuminate\Database\Eloquent\Builder|Milestone whereDueOn($value)
 * @property int $milestone_id
 * @method static \Illuminate\Database\Eloquent\Builder|Milestone whereMilestoneId($value)
 * @mixin \Eloquent
 */
class Milestone extends Model
{
    use HasFactory;

    protected $fillable = [
        "title",
        "url",
        "state",
        "description",
        "progress",
        "due_on",
        "milestone_id",
        "open_issues_count",
        "closed_issues_count",
        "repository_id"
    ];

    public function repository(): BelongsTo
    {
        return $this->belongsTo(Repository::class);
    }
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
