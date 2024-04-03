<?php

namespace Mediamouse\Users\Models;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int id
 * @property string loggable_type
 * @property int loggable_id
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @property int created_by
 * @property string description
 * @property string old_value
 * @property string new_value
 *
 * @property User createdBy
 */
class ChangeLog extends Model
{
    public function createdBy(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

}
