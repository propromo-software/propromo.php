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
 * @method static \Illuminate\Database\Eloquent\Builder|Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project query()
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereProjectIdentification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereProjectUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereUserId($value)
 * @property string $organisation_name
 * @property int $project_id
 * @property int $project_view
 * @property string $project_hash
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereOrganisationName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereProjectHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereProjectView($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Milestone> $milestones
 * @property-read int|null $milestones_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @property string|null $readme
 * @property bool|null $public
 * @property string|null $title
 * @property string|null $url
 * @property string|null $short_description
 * @method static \Illuminate\Database\Eloquent\Builder|Project wherePublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereReadme($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereShortDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereUrl($value)
 * @mixin \Eloquent
 */
class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        "project_url",
        "project_hash",
        "organisation_name",
        "title",
        "url",
        "public",
        "readme",
        "short_description",
        "project_identification",
        "project_view"
    ];

    /*
     *  "title": "propromo",
        "shortDescription": "Your client wants to be involved. Propromo makes it possible.",
        "url": "https://github.com/orgs/propromo-software/projects/1",
        "public": true,
        "createdAt": "2023-12-19T08:34:38Z",
        "updatedAt": "2024-02-12T22:46:24Z",
        "closedAt": null,
        "readme": "# Propromo\n\n## Contact\n\nwrite us: propromo.business@skiff.com / propromo.security@skiff.com"
     *
     * */

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class,'project_user', 'project_id', 'user_id');
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(Milestone::class);
    }
}
