<?php

namespace Mediamouse\Users\Models;

use Carbon\Carbon;
use Mediamouse\Laravel\Models\Model;

/**
 * @property int id
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @property int start
 * @property int end
 * @property string country_iso
 * @property string country_name
 */
class IpLocatorListing extends Model
{

    protected $fillable = [
    ];

    protected $casts = [
    ];

}
