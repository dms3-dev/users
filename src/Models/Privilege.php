<?php

namespace Mediamouse\Users\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Mediamouse\Laravel\Models\Model;

/**
 * @property string policy_key
 * @property string privilege
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @property Policy policy
 * @property Collection<Group> groups
 */

class Privilege extends Model
{
    public function policy(): BelongsTo
    {
        return $this->belongsTo(Policy::class);
    }

    public function privileges() : BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_has_privilege', 'privilege_id', 'group_key');
    }
}
