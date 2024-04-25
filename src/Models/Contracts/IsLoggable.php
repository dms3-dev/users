<?php

namespace Mediamouse\Users\Models\Contracts;

use Mediamouse\Users\Models\ChangeLog;
use Mediamouse\Users\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface IsLoggable
{

    public function logs(): MorphMany;
    public function addLog(
        string $description = null,
        string $oldValue = null,
        string $newValue = null,
        ?User $user = null,
        ?ChangeLog $changeLog = null
    ): static;

}
