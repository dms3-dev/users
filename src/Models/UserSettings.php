<?php

namespace Mediamouse\Users\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Mediamouse\Laravel\Models\Model;
use Mediamouse\Users\Factories\GroupFactory;
use Illuminate\Support\Collection;

/**
 * @property int id
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property int user_id
 * @property string group
 * @property string name
 * @property boolean locked
 * @property mixed payload
 *
 */
class UserSettings extends Model
{
    use HasFactory;

    protected $casts = [
        'locked' => 'bool',
    ];
}
