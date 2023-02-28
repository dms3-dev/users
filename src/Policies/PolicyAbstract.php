<?php

namespace Mediamouse\Users\Policies;

use App\Models\User;
use Mediamouse\Laravel\Models\Model;
use Mediamouse\Users\Enums\PolicyPrivilege;
use Mediamouse\Users\Models\Contracts\HasUserDependentPolicy;

abstract class PolicyAbstract {

    protected function type(): string {
        return get_class($this);
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPrivilege($this->type(), PolicyPrivilege::VIEW_ANY);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Model $model): bool
    {
        if($model instanceof HasUserDependentPolicy) {
            return $model->userIsAllowed($user, $this->type(), PolicyPrivilege::VIEW);
        }
        return $user->hasPrivilege($this->type(), PolicyPrivilege::VIEW);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPrivilege($this->type(), PolicyPrivilege::CREATE);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Model $model): bool
    {
        if($model instanceof HasUserDependentPolicy) {
            return $model->userIsAllowed($user, $this->type(), PolicyPrivilege::UPDATE);
        }
        return $user->hasPrivilege($this->type(), PolicyPrivilege::UPDATE);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Model $model): bool
    {
        if($model instanceof HasUserDependentPolicy) {
            return $model->userIsAllowed($user, $this->type(), PolicyPrivilege::DELETE);
        }
        return $user->hasPrivilege($this->type(), PolicyPrivilege::DELETE);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Model $model): bool
    {
        if($model instanceof HasUserDependentPolicy) {
            return $model->userIsAllowed($user, $this->type(), PolicyPrivilege::RESTORE);
        }
        return $user->hasPrivilege($this->type(), PolicyPrivilege::RESTORE);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Model $model): bool
    {
        if($model instanceof HasUserDependentPolicy) {
            return $model->userIsAllowed($user, $this->type(), PolicyPrivilege::FORCE_DELETE);
        }
        return $user->hasPrivilege($this->type(), PolicyPrivilege::FORCE_DELETE);
    }
}
