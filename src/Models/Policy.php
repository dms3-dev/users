<?php

namespace Mediamouse\Users\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Mediamouse\Laravel\Models\Model;
use Mediamouse\Users\Factories\PolicyFactory;

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
    use HasFactory;

    protected $primaryKey = 'policy';

    protected $keyType = 'string';
    public $incrementing = false;

    public function privileges(): HasMany
    {
        return $this->hasMany(Privilege::class);
    }

    protected static function newFactory(): PolicyFactory
    {
        return PolicyFactory::new();
    }
}
