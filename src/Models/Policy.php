<?php

namespace Mediamouse\Users\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Mediamouse\Laravel\Models\Model;

/**
 * @property string policy
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property string name
 *
 * @property Collection<Privilege> privileges
 */

class Policy extends Model
{
    protected $primaryKey = 'policy';

    protected $keyType = 'string';
    public $incrementing = false;

    public function privileges(): HasMany
    {
        return $this->hasMany(Privilege::class);
    }
}
