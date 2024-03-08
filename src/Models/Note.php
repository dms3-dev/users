<?php

namespace Mediamouse\Users\Models;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Mediamouse\Laravel\Models\Model;
use Mediamouse\Users\Enums\NoteStatus;
use Mediamouse\Users\Enums\NoteType;
use Mediamouse\Users\Factories\NoteFactory;
use Mediamouse\Users\Models\Contracts\WithNotes;

/**
 * @property int id
 * @property int has_notes_id
 * @property string has_notes_type
 *
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Carbon milestone_at
 * @property NoteType type
 * @property NoteStatus status
 * @property string content
 *
 * @property User user
 * @property WithNotes notable
 */

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'milestone_at',
        'type',
        'status',
        'content',
        'assigned_to',
    ];

    protected $casts = [
        'status' => NoteStatus::class,
        'type' => NoteType::class,
        'milestone_at' => 'datetime',
    ];

    public function notable(): BelongsTo {
        return $this->belongsTo($this->has_notes_type, 'has_notes_id');
    }

    public function user(): BelongsTo {
        return $this->belongsTo(\Mediamouse\Users\Models\User::class);
    }


    protected static function newFactory()
    {
        return NoteFactory::new();
    }
}
