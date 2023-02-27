<?php

namespace Mediamouse\Users\Models\Contracts;

use Mediamouse\Users\Enums\PolicyPrivilege;
use Mediamouse\Users\Models\User;

interface HasUserDependentPolicy {
    public function userIsAllowed(User $user, string $policy, PolicyPrivilege $privilege): bool;
}
