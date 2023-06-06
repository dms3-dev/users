<?php

namespace Mediamouse\Users\Models;

use Carbon\Carbon;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\Builder;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Mediamouse\Mails\Enums\MailPriority;
use Mediamouse\Users\Enums\LoginAttemptStatus;
use Mediamouse\Users\Enums\PasswordResetStatus;
use Mediamouse\Users\Enums\PolicyPrivilege;
use Mediamouse\Users\Enums\UserRole;
use Mediamouse\Users\Enums\UserStatus;
use Mediamouse\Users\Enums\UserTwoFactor;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Mediamouse\Users\Factories\UserFactory;
use Mediamouse\Users\MailTemplate\LoginChallengeMail;
use Mediamouse\Users\MailTemplate\PasswordIsChangedMail;
use Mediamouse\Users\MailTemplate\ResetPasswordLinkMail;
use Mediamouse\Users\Settings\UserManagementSettings;

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
class User extends Authenticatable implements FilamentUser
{
    use HasFactory;
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

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
        'phone_verified_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
        'remember_token',
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

    private function createChallengeCode() : string {
        return (string) rand(100000, 999999);
    }

    private function createPasswordResetToken() : PasswordReset
    {
        $token = new PasswordReset();

        $token->user_id = $this->id;
        $token->token = Str::uuid();
        $token->status = PasswordResetStatus::CREATED;

        $token->save();

        return $token;
    }

    private function createPasswordLink() : string {
        $token = $this->createPasswordResetToken();

        return env('APP_URL', request()->schemeAndHttpHost()) . '/' . config('filament.path') . '/reset-password/' . $token->token;
    }

    public function mailableAddress(): Address
    {
        return new Address($this->email, $this->name);
    }

    public function createLoginAttempt(): LoginAttempt {
        $attempt = new LoginAttempt();

        $attempt->user_id = $this->id;
        $attempt->status = LoginAttemptStatus::CREATED;
        $attempt->ip = request()->ip();
        $attempt->token = $this->createChallengeCode();

        $attempt->save();

        return $attempt;
    }

    public function sendLoginChallenge(LoginAttempt $attempt): static {
        $challenge = $attempt->token;

        $template = LoginChallengeMail::template();

        $template->translation($this->language_iso)->send(
            $this->mailableAddress(),
            [
                'name' => $this->name,
                'email' => $this->email,
                'ip' => request()->server('REMOTE_ADDR'),
                'challenge' => $challenge,
            ],
            MailPriority::URGENT);

        $attempt->status = LoginAttemptStatus::PENDING_2FA;
        $attempt->save();


        return $this;
    }

    public function sendForgotPasswordLink(): void {
        $link = $this->createPasswordLink();

        $template = ResetPasswordLinkMail::template();

        $template->translation($this->language_iso)->send(
            $this->mailableAddress(),
            [
                'name' => $this->name,
                'email' => $this->email,
                'ip' => request()->server('REMOTE_ADDR'),
                'link' => $link,
            ],
            MailPriority::URGENT);
    }

    public function sendFailedLoginAttempt(): void {
        $nr_of_attempts = $this->nrOfFailedAttemptsFrom(request()->ip());

        $template = ResetPasswordLinkMail::template();

        if(in_array($nr_of_attempts, [1,5]) || ($nr_of_attempts > 1 && $nr_of_attempts % 10 === 0)) {
            $template->translation($this->language_iso)->send(
                $this->mailableAddress(),
                [
                    'name' => $this->name,
                    'email' => $this->email,
                    'ip' => request()->server('REMOTE_ADDR'),
                    'count' => $nr_of_attempts,
                ]);
        }
        if($nr_of_attempts > 1 && $nr_of_attempts % 10 === 0) {
            $template->translation($this->language_iso)->send(
                new Address('alert@mediamouse.nl', 'MediaMouse Alert'),
                [
                    'name' => $this->name,
                    'email' => $this->email,
                    'ip' => request()->server('REMOTE_ADDR'),
                    'count' => $nr_of_attempts,
                ]);
        }
    }


    private function nrOfFailedAttemptsFrom(?string $ip)
    {

    }

    public function informPasswordHasBeenReset(): void {
        $template = PasswordIsChangedMail::template();

        $template->translation($this->language_iso)->send(
            $this->mailableAddress(),
            [
                'name' => $this->name,
                'email' => $this->email,
                'ip' => request()->server('REMOTE_ADDR'),
            ]);
    }

    public function lastLoginAttempt(): HasOne
    {
        return $this->hasOne(LoginAttempt::class)->latestOfMany();
    }

    public function privileges() {
        return $this->hasManyThrough(GroupHasPolicy::class, UserMemberOfGroup::class, 'user_id', 'group_key', 'id', 'group_key');
    }

    public function hasPrivilege(string $policy_class, PolicyPrivilege $privilege): bool
    {
        if($this->role === UserRole::SA) return true;
        $value = $privilege->value;
        $policy = $this->privileges()->where('policy', $policy_class)->first();
        if($policy === null && $this->groups()->count() > 0) {
            try {
                $newPolicy = new Policy();
                $newPolicy->policy = $policy_class;
                $newPolicy->name = $policy_class;
                $newPolicy->save();
            } catch(\Throwable $e) { }

            /** @var Group $group */
            foreach($this->groups as $group) {
                $group->createPolicies();
            }
        }
        return $policy !== null && $policy?->$value;
    }

    /**
     * @return bool
     *
     * @todo Make sure user can be blocked
     */
    public function isBlocked() : bool {
        return $this->status !== UserStatus::ACTIVE;
    }

    public function canAccessFilament(): bool
    {
        return in_array($this->role, [UserRole::ADMINISTRATOR, UserRole::SA]) && !$this->isBlocked();
    }

    protected static function newFactory()
    {
        return UserFactory::new();
    }

    private function needsResetInDays(): int {
        if($this->two_factor == UserTwoFactor::NONE) {
            return app(UserManagementSettings::class)->reset_password_every_x_days_without_2fa;
        }
        return app(UserManagementSettings::class)->reset_password_every_x_days_with_2fa;
    }

    public function passwordNeedsReset() : ?PasswordReset {
//        return $this->createPasswordResetToken();
        if($this->needsResetInDays() === 0) return null;

        /** @var Carbon $date */
        $date = $this->passwords()->latest()->first()?->created_at ?? $this->created_at;

        if($date->addDays($this->needsResetInDays()) > Carbon::now()) {
            return null;
        }

        return $this->createPasswordResetToken();
    }

    public function validatePassword($password): bool
    {
        return Hash::check($password, $this->password);
    }

    public function updatePassword(string $password): bool
    {
        $this->password = Hash::make($password);
        $this->save();

        $newPassword = new Password();
        $newPassword->password = $this->password;
        $newPassword->user_id = $this->id;

        $newPassword->save();

        $this->informPasswordHasBeenReset();
        return true;
    }

    public function updateUserSetting($property, $value) {
        [$group, $name] = explode('.', $property);

        $setting = UserSettings::query()
            ->where('user_id', $this->id)
            ->where('group', $group)
            ->where('name', $name)
            ->first();

        if($setting === null) {
            $setting = new UserSettings();
            $setting->user_id = $this->id;
            $setting->group = $group;
            $setting->name = $name;
        }

        $setting->payload = json_encode($value);
        $setting->save();

    }

    public function getUserSetting($property) {
        [$group, $name] = explode('.', $property);

        $setting = UserSettings::query()
            ->where('user_id', $this->id)
            ->where('group', $group)
            ->where('name', $name)
            ->first('payload');

        if($setting === null) return null;
        return json_decode($setting->getAttribute('payload'));

    }
}
