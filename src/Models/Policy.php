<?php

namespace Mediamouse\Users\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Mediamouse\Laravel\Models\Model;

/**
 * @property string policy
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property string name
 */

class Policy extends Model
{
    public function privileges(): HasMany
    {
        return $this->hasMany(Privilege::class);
    }
}
