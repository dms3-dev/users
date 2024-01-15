<?php

namespace Mediamouse\Users\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Mediamouse\Laravel\Models\Model;
use Mediamouse\Users\Enums\SystemHealthStatus;
use Mediamouse\Users\Factories\GroupFactory;
use Psy\Util\Json;
use Illuminate\Support\Collection;

/**
 * @property int id
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property int system_health_id
 * @property SystemHealthStatus status
 *
 *
 */
class SystemHealthStats extends Model
{
    use HasFactory;

    protected $casts =
        [
            'status' => SystemHealthStatus::class,
        ];

    protected $fillable =
        [
            'system_health_id',
            'status',
        ];

    public function systemHealth() : BelongsTo {
        return $this->belongsTo(SystemHealth::class);
    }

}
