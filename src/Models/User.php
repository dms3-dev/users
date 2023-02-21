<?php

namespace Mediamouse\Users\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Mediamouse\Laravel\Models\Model;
use Mediamouse\Users\Enums\UserRole;
use Mediamouse\Users\Enums\UserStatus;
use Mediamouse\Users\Enums\UserTwoFactor;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property int id
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property UserStatus status
 * @property string username
 * @property string name
 * @property string email
 * @property Carbon email_verified_at
 * @property string password
 * @property string remember_token
 * @property UserRole role
 * @property UserTwoFactor two_factor
 *
 * @property string language_iso
 *
 * @property Collection<Password> passwords
 * @property Collection<PasswordReset> passwordResets
 * @property Collection<LoginAttempt> loginAttempts
 * @property Collection<Group> groups
 *
 * @property Language language
 * @property LoginAttempt lastLoginAttempt
 */
class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'username',
        'name',
        'email',
        'role',
        'two_factor',
        'language_iso',
        'groups',
        'status',
    ];

    protected $casts = [
        'status' => UserStatus::class,
        'role' => UserRole::class,
        'two_factor' => UserTwoFactor::class,
        'email_verified_at' => 'datetime',
    ];

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function loginAttempts(): HasMany
    {
        return $this->hasMany(LoginAttempt::class);
    }

    public function passwords(): HasMany
    {
        return $this->hasMany(Password::class);
    }

    public function passwordResets(): HasMany
    {
        return $this->hasMany(PasswordReset::class);
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'user_memberof_group', 'user_id', 'group_key');
    }

    public function allGroups()
    {
        $result = DB::query()
            ->from('groups')
            ->leftJoin('user_memberof_group', function(Builder $query) {

                $query->whereRaw('user_memberof_group.group_key = groups.key');
                $query->whereRaw('user_memberof_group.user_id = ' . $this->id);
            })
                ;

        $result = $this->hasMany(UserMemberOfGroup::class)
            ->rightJoin('groups', function(Builder $query) {
                $query->whereRaw('user_memberof_group.group_key = groups.key');
                $query->whereRaw('user_memberof_group.user_id = ' . $this->id);
            })
            ;
        return $result;
    }

    public function lastLoginAttempt(): HasOne
    {
        return $this->hasOne(LoginAttempt::class)->latestOfMany();
    }


}
