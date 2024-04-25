<?php

namespace Mediamouse\Users\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use Mediamouse\Users\Models\Note;

/**
 * @property Collection<Note> notes
 */
interface WithNotes {

    public function notes(): MorphMany;
    public function copyNotesFrom(WithNotes $oldWithNotes): static;
    public function moveNotesFrom(WithNotes $oldWithNotes): static;

    public function labelColor(): string;
    public function labelText(): string;
    public function noteListLink(): string;

    public function qualifiedName(): string;

}
