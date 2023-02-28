<?php

namespace Mediamouse\Users\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Mediamouse\Users\Enums\LanguageStatus;
use Mediamouse\Laravel\Models\Model;
use Mediamouse\Users\Factories\LanguageFactory;

/**
 * @property string iso
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property string name
 * @property LanguageStatus status
 * @property int sort
 *
 * @property Collection<User> users
 */
class Language extends Model
{
    use HasFactory;

    protected $primaryKey = 'iso';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'iso',
        'name',
    ];

    protected $casts = [
        'status' => LanguageStatus::class,
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public static function make(string $iso, string $name, LanguageStatus $status = LanguageStatus::ACTIVE, int $sort = 0) {
        $self = self::findOrCreate($iso);

        $self->name = $name;
        $self->status = $status;
        $self->sort = $sort;

        $self->save();

        return $self;
    }

    protected static function newFactory(): LanguageFactory
    {
        return LanguageFactory::new();
    }
}
