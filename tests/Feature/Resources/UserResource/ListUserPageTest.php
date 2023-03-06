<?php

namespace Mediamouse\Users\Tests\Feature\Resources\UserResource;

use App\Filament\Resources\CustomerResource;
use App\Filament\Resources\CustomerResource\Pages\ListCustomers;
use App\Models\Customer;
use App\Models\User;
use Mediamouse\Filament\Testing\Traits\FilamentTable;
use Mediamouse\Filament\Testing\Traits\ResourcePage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\Rules\Enum;
use Mediamouse\Users\Enums\LoginAttemptStatus;
use Mediamouse\Users\Filament\Resources\UserResource;
use Mediamouse\Users\Filament\Resources\UserResource\Pages\ListUsers;
use Mediamouse\Users\Models\Group;
use Mediamouse\Users\Models\Language;
use Mediamouse\Users\Models\LoginAttempt;
use Tests\TestCase;

class ListUserPageTest extends TestCase
{
    use ResourcePage;
    use FilamentTable;
    use RefreshDatabase;


    private function createGroup($id) : void {
        $group = new Group();
        $group->key = $id;
        $group->name = $id . '_name';
        $group->save();
    }

    protected function setUpPage($count = 10): void
    {
        Language::make('nl', 'Nederlands');
        $this->records = User::factory()->count($count - User::query()->count())->create();

        $this->createGroup('K1');
        $this->createGroup('K2');
        $this->createGroup('K3');

        $this->liveWireClass = ListUsers::class;
        $this->url = UserResource::getUrl();
        $this->records = User::all();
        $this->query = User::query();
    }

    protected function getResourceUrlForAction($action, $record) : string {
        return UserResource::getUrl($action, ['record' => $record]);
    }


    public function testTheColumnUserNameIsVisibleByDefault() {             $this->seeIfColumnIsVisibleByDefault('username'); }
    public function testTheColumnNameIsVisibleByDefault() {                 $this->seeIfColumnIsVisibleByDefault('name'); }
    public function testTheColumnEmailIsVisibleByDefault() {                $this->seeIfColumnIsVisibleByDefault('email'); }
    public function testTheColumnRoleIsVisibleByDefault() {                 $this->seeIfColumnIsVisibleByDefault('role'); }
    public function testTheColumnStatusIsVisibleByDefault() {               $this->seeIfColumnIsVisibleByDefault('status'); }

    public function testTheColumnEmailVerifiedAtIsNotVisibleByDefault() {   $this->seeIfColumnIsNotVisibleByDefault('email_verified_at'); }
//    public function testTheColumnTwoFactorIsNotVisibleByDefault() {         $this->seeIfColumnIsNotVisibleByDefault('two_factor'); }  // Werkt nog niet
//    public function testTheColumnLanguageIsNotVisibleByDefault() {          $this->seeIfColumnIsNotVisibleByDefault('language.name'); }
//    public function testTheColumnGroupsIsNotVisibleByDefault() {            $this->seeIfColumnIsNotVisibleByDefault('Groups'); } // Werkt nog niet

    public function testTheColumnUserNameIsSortable() {                     $this->seeIfColumnIsSortable('username'); }
    public function testTheColumnNameIsSortable() {                         $this->seeIfColumnIsSortable('name'); }
    public function testTheColumnEmailIsSortable() {                        $this->seeIfColumnIsSortable('email'); }
    public function testTheColumnEmailVerifiedAttIsSortable() {             $this->seeIfColumnIsSortable('email_verified_at'); }
    public function testTheColumnTwoFactorIsSortable() {                    $this->seeIfColumnIsSortable('two_factor'); }
    public function testTheColumnRoleIsSortable() {                         $this->seeIfColumnIsSortable('role'); }
//    public function testTheColumnLanguageIsoIsSortable() {                  $this->seeIfColumnIsSortable('language.name'); } // Werkt nog niet
    public function testTheColumnStatusIsSortable() {                       $this->seeIfColumnIsSortable('status'); }
//    public function testTheColumnLastLoginAttemptIsSortable() {             $this->seeIfColumnIsSortable('lastLoginAttempt'); } // Werkt nog niet

    public function testTheColumnUserNameIsSortableDesc() {                 $this->seeIfColumnIsSortableDesc('username'); }
    public function testTheColumnNameIsSortableDesc() {                     $this->seeIfColumnIsSortableDesc('name'); }
    public function testTheColumnEmailIsSortableDesc() {                    $this->seeIfColumnIsSortableDesc('email'); }
    public function testTheColumnEmailVerifiedAttIsSortableDesc() {         $this->seeIfColumnIsSortableDesc('email_verified_at'); }
    public function testTheColumnTwoFactorIsSortableDesc() {                $this->seeIfColumnIsSortableDesc('two_factor'); }
    public function testTheColumnRoleIsSortableDesc() {                     $this->seeIfColumnIsSortableDesc('role'); }
//    public function testTheColumnLanguageIsoIsSortableDesc() {              $this->seeIfColumnIsSortableDesc('language.name'); } // Werkt nog niet
    public function testTheColumnStatusIsSortableDesc() {                   $this->seeIfColumnIsSortableDesc('status'); }
//    public function testTheColumnLastLoginAttemptIsSortableDesc() {         $this->seeIfColumnIsSortableDesc('lastLoginAttempt'); } // Werkt nog niet

    public function testTheColumnUserNameIsSearchable() {                   $this->seeIfColumnIsSearchable('username'); }
    public function testTheColumnNameIsSearchable() {                       $this->seeIfColumnIsSearchable('name'); }
    public function testTheColumnEmailIsSearchable() {                      $this->seeIfColumnIsSearchable('email'); }
//    public function testTheColumnRoleIsSearchable() {                       $this->seeIfColumnIsSearchable('role'); } // Werkt nog niet
    public function testTheColumnLanguageIsoIsSearchable() {                $this->seeIfColumnIsSearchable('language.name'); }
//    public function testTheColumnStatusIsSearchable() {                     $this->seeIfColumnIsSearchable('status'); } // Werkt nog niet

    public function testNewUserActionExists() {
        $this->seeIfPageTableHasAction('create');
    }

    public function testNewUserActionLeadsToNewUserPage() {
        $this->seeIfPageTableActionLinksTo('create', UserResource::getUrl('create'));
    }

    // Werkt nog niet
//    public function testViewActionExists() {
//        $this->seeIfTableHasAction('view');
//        $this->renderTable(1)->assertTableActionExists('view');
//    }

    public function testViewActionLinksToViewUser() {
        $this->seeIfTableActionLinksToUrl('view');
    }
}
