<?php

namespace Mediamouse\Users\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Mediamouse\Laravel\Models\Model;

/**
 * @property string iso
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property string name
 * @property string status
 * @property int sort
 */

class Language extends Model
{


//    public function users(): HasMany
//    {
//        return $this->hasMany(Users::class);
//    }
}