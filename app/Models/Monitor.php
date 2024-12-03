<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use PhpParser\Builder;

/**
 * Post
 *
 * @mixin Builder
 * @property int $id
 * @property int $user_id
 * @property string $project_url
 * @property string $project_identification
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Monitor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Monitor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Monitor query()
 * @method static \Illuminate\Database\Eloquent\Builder|Monitor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monitor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monitor whereProjectIdentification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monitor whereProjectUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monitor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monitor whereUserId($value)
 * @property string $organization_name
 * @property int $project_id
 * @property int $project_view
 * @property string $project_hash
 * @method static \Illuminate\Database\Eloquent\Builder|Monitor whereOrganisationName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monitor whereProjectHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monitor whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monitor whereProjectView($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Milestone> $milestones
 * @property-read int|null $milestones_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @property string|null $readme
 * @property bool|null $public
 * @property string|null $title
 * @property string|null $url
 * @property string|null $short_description
 * @method static \Illuminate\Database\Eloquent\Builder|Monitor wherePublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monitor whereReadme($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monitor whereShortDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monitor whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monitor whereUrl($value)
 * @property string|null $pat_token
 * @method static \Illuminate\Database\Eloquent\Builder|Monitor wherePatToken($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Repository> $repositories
 * @property-read int|null $repositories_count
 * @property string $monitor_hash
 * @method static \Illuminate\Database\Eloquent\Builder|Monitor whereMonitorHash($value)
 * @property string $type
 * @property string|null $login_name
 * @method static \Illuminate\Database\Eloquent\Builder|Monitor whereLoginName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monitor whereOrganizationName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monitor whereType($value)
 * @mixin \Eloquent
 */
class Monitor extends Model
{
    use HasFactory;

    protected $fillable = [
        "project_url",
        "monitor_hash",
        "organization_name",
        "title",
        "public",
        "pat_token",
        'login_name',
        "type",
        "readme",
        "short_description",
        "project_identification",
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'monitor_user', 'monitor_id', 'user_id');
    }

    public function repositories(): HasMany
    {
        return $this->hasMany(Repository::class);
    }

    public function deployments(): HasMany
    {
        return $this->hasMany(Deployment::class);
    }
}
