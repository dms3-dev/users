<?php

namespace Mediamouse\Users\Filament\Resources\UserResource\Pages;

use Filament\Pages\Actions\Action;
use Mediamouse\Users\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

}
