<?php

namespace Mediamouse\Users\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Mediamouse\Laravel\Models\Model;
use Mediamouse\Users\Enums\PolicyPrivilege;
use Mediamouse\Users\Factories\PrivilegeFactory;

/**
 * @property string policy_key
 * @property PolicyPrivilege privilege
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @property Policy policy
 * @property Collection<Group> groups
 */

class Privilege extends Model
{
    use HasFactory;

    protected $casts = [
        'privilege' => PolicyPrivilege::class,
    ];

    public function policy(): BelongsTo
    {
        return $this->belongsTo(Policy::class);
    }

    public function privileges() : BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_has_policies', 'privilege_id', 'group_key');
    }

    protected static function newFactory(): PrivilegeFactory
    {
        return PrivilegeFactory::new();
    }
}
