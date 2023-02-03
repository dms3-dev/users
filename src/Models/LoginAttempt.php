<?php

namespace Mediamouse\Users\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Mediamouse\Laravel\Models\Model;

/**
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property string status
 * @property string ip
 * @property string token
 * @property int id
 *
 * @property
 * @property
 */

class LoginAttempt extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
