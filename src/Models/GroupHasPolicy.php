<?php

namespace Mediamouse\Users\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    public function name(): Attribute
    {
        return Attribute::make(
            get: fn () => class_exists($this->policy) ? (new ($this->policy)())->name() : '*** Policy not found'
        );
    }
}
