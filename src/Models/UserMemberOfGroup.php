<?php

namespace Mediamouse\Users\Models;

use Carbon\Carbon;
use Mediamouse\Laravel\Models\Model;

/**
 * @property int user_id
 * @property string group_key
 *
 * @property Carbon created_at
 * @property Carbon updated_at
 */

class UserMemberOfGroup extends Model
{
    protected $table = 'user_memberof_group';
}
