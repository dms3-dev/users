<?php

namespace Mediamouse\Users\Models;

use Carbon\Carbon;
use Mediamouse\Laravel\Models\Model;

/**
 * @property string group_key
 * @property string policy
 *
 * @property bool view_any
 * @property bool view
 * @property bool create
 * @property bool update
 * @property bool delete
 * @property bool restore
 * @property bool force_delete
 * @property bool reorder
 *
 * @property int id
 *
 * @property Policy policyObject
 * @property Carbon created_at
 * @property Carbon updated_at
 */

class GroupHasPolicy extends Model
{
    protected $table = 'group_has_policies';

    protected $fillable = [
        'policy',
    ];

    public function policyObject() {
        return $this->belongsTo(Policy::class, 'policy', 'policy');
    }
}
