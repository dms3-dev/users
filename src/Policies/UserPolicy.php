<?php


namespace Mediamouse\Users\Policies;


use App\Models\User as LoggedInUser;
use Mediamouse\Users\Models\User;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Mediamouse\Laravel\Models\Model;
use Mediamouse\Users\Enums\UserRole;


class UserPolicy extends PolicyAbstract

{

    protected string $name = 'User Management';


    public function update(LoggedInUser $user, Model|Authenticatable $model): bool
    {
        if (parent::update($user, $model) && $model instanceof User) {
            if ($user->role === UserRole::SA) return true;
            if ($model->role === UserRole::ADMINISTRATOR) return true;
        }
        return false;
    }

    public function view(LoggedInUser $user, Model|Authenticatable $model): bool
    {
        if (parent::view($user, $model) && $model instanceof User) {
            if ($user->role === UserRole::SA) return true;
            if ($model->role === UserRole::ADMINISTRATOR) return true;
        }
        return false;
    }

    public function delete(LoggedInUser $user, Model|Authenticatable $model): bool
    {
        if (parent::delete($user, $model) && $model instanceof User) {
            if ($user->role === UserRole::SA) return true;
            if ($model->role === UserRole::ADMINISTRATOR) return true;
        }
        return false;
    }

    public function forceDelete(LoggedInUser $user, Model|Authenticatable $model): bool
    {
        if (parent::forceDelete($user, $model) && $model instanceof User) {
            if ($user->role === UserRole::SA) return true;
            if ($model->role === UserRole::ADMINISTRATOR) return true;
        }
        return false;
    }

    public function restore(LoggedInUser $user, Model|Authenticatable $model): bool
    {
        if (parent::restore($user, $model) && $model instanceof User) {
            if ($user->role === UserRole::SA) return true;
            if ($model->role === UserRole::ADMINISTRATOR) return true;
        }
        return false;
    }
}
