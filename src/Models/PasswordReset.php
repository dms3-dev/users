<?php

namespace Mediamouse\Users\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Mediamouse\Laravel\Models\Model;
use Mediamouse\Users\Enums\PasswordResetStatus;
use App\Models\User as AppUser;

/**
 * @property Carbon user_id
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property string token
 *
 * @property PasswordResetStatus status
 *
 * @property AppUser user
 */

class PasswordReset extends Model
{
    protected $casts = [
        'status' => PasswordResetStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(AppUser::class);
    }
}
