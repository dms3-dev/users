<?php

namespace Mediamouse\Users\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Mediamouse\Laravel\Models\Model;
use Mediamouse\Users\Factories\GroupFactory;
use Ramsey\Collection\Collection;

/**
 * @property string key
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property string name
 *
 * @property Collection<User> users
 * @property Collection<GroupHasPolicy> policies
 */
class Group extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $table = 'groups';
    protected $primaryKey = 'key';
    protected $keyType = 'string';
    protected $fillable =
        [
            'key',
            'name',
            'privileges',
        ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_memberof_group', 'group_key', 'user_id');
    }

    public function policies(): HasMany
    {
        return $this->hasMany(GroupHasPolicy::class);
    }

    /** @noinspection PhpIncompatibleReturnTypeInspection */
    public function findPolicy(Policy|string $policy): ?GroupHasPolicy {
        $policy_name = is_string($policy) ? $policy : $policy->policy;

        return $this->hasMany(GroupHasPolicy::class)->where('policy', $policy_name)->first();
    }

    public function findOrCreatePolicy(Policy|string $policy): GroupHasPolicy
    {
        $group_policy = $this->findPolicy($policy);

        if($group_policy === null) {
            $group_policy = new GroupHasPolicy();

            $group_policy->group_key = $this->key;
            $group_policy->policy = is_string($policy) ? $policy : $policy->policy;

            $group_policy->save();
        }

        return $group_policy;
    }

    public function createPolicies() : static {
        foreach(Policy::query()->get() as $policy) {
            $this->findOrCreatePolicy($policy);
        }

        return $this;
    }

    public function save(array $options = []): bool
    {
        $result = parent::save($options);

        $this->createPolicies();

        return $result;
    }

    protected static function newFactory()
    {
        return GroupFactory::new();
    }



}
