<?php

namespace Mediamouse\Users\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Mediamouse\Laravel\Models\Model;

/**
 * @property string type
 * @property Carbon created_at
 * @property Carbon updated_at
 */

class Privilege extends Model
{
    public function policies(): BelongsTo
    {
        return $this->belongsTo(Policy::class);
    }
}
