<?php

namespace Mediamouse\Users\Models\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Mediamouse\Users\Filament\RelationManagers\NotesRelationManager;
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
    public function labelColor(): string{
        return 'primary';
    }
    public function labelText(): string{
        $class_path = explode('\\', static::class);
        $end = end($class_path);
        return strtolower($end);
    }
    public function noteListLink(): string{
        $class_path = explode('\\', static::class);
        $class = '\\App\\Filament\\Resources\\' . end($class_path) . 'Resource';
        return $class::getUrl('view', [
            'record' => $this,
            'activeRelationManager' => array_search(NotesRelationManager::class, $class::getRelations())
        ]);
    }
}
