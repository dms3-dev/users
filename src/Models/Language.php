<?php

namespace Mediamouse\Users\Models;

use Carbon\Carbon;
use Mediamouse\Users\Enums\Status;
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
    protected $primaryKey = 'iso';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $casts = [
        'status' => Status::class,
    ];

//    public function users(): HasMany
//    {
//        return $this->hasMany(Users::class);
//    }

    public static function make(string $iso, string $name, Status $status = Status::ACTIVE, int $sort = 0) {
        $self = self::findOrCreate($iso);

        $self->name = $name;
        $self->status = $status;
        $self->sort = $sort;

        $self->save();

        return $self;
    }
}
