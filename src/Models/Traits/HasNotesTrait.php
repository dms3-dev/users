<?php

namespace Mediamouse\Users\Models\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Mediamouse\Users\Models\Contracts\WithNotes;
use Mediamouse\Users\Models\Note;

trait HasNotesTrait {

    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'has_notes');
    }

    public function copyNotesFrom(WithNotes $oldWithNotes): static
    {
        /** @var Note $note */
        foreach ($oldWithNotes->notes as $note) {
            $newNote = $note->replicate();
            $newNote->has_notes_id = $this->id;
            $newNote->has_notes_type = get_class($this);
            $newNote->save();
        }
        return $this;
    }

    public function moveNotesFrom(WithNotes $oldDonor): static
    {

        /** @var Note $note */
        foreach ($oldDonor->notes as $note) {
            $newNote = $note->replicate();
            $newNote->has_notes_id = $this->id;
            $newNote->has_notes_type = get_class($this);
            $newNote->save();
        }

        return $this;
    }
}
