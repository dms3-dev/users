<?php

namespace Mediamouse\Users\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Mediamouse\Laravel\Models\Model;
use Mediamouse\Users\Enums\LoginAttemptStatus;

/**
 * @property int user_id
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @property LoginAttemptStatus status
 *
 * @property string ip
 * @property string token
 *
 * @property User user
 */

class LoginAttempt extends Model
{
    protected $casts = [
        'status' => LoginAttemptStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
