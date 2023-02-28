<?php

namespace Mediamouse\Users\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Mediamouse\Laravel\Models\Model;
use Ramsey\Collection\Collection;

/**
 * @property string key
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property string name
 *
 * @property Collection<User> users
 * @property Collection<Privilege> privileges
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

    public function privileges(): BelongsToMany
    {
        return $this->belongsToMany(Privilege::class, 'group_has_privilege', 'group_key', 'privilege_id');
    }


}
