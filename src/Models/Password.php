<?php

namespace Mediamouse\Users\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Mediamouse\Laravel\Models\Model;

/**
 * @property int user_id
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property string password
 *
 * @property User user
 */

class Password extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
