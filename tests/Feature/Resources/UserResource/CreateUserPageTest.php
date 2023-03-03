<?php

namespace Mediamouse\Users\Tests\Feature\Resources\UserResource;

use Mediamouse\Users\Enums\LanguageStatus;
use Mediamouse\Users\Enums\UserRole;
use Mediamouse\Users\Enums\UserStatus;
use Mediamouse\Users\Enums\UserTwoFactor;
use Mediamouse\Users\Filament\Resources\UserResource;
use App\Models\User;
use Mediamouse\Filament\Testing\Traits\FilamentForm;
use Mediamouse\Filament\Testing\Traits\ResourcePage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\Rules\Enum;
use Mediamouse\Users\Models\Group;
use Mediamouse\Users\Models\Language;
use Tests\TestCase;

/**
 * @property User record
 */
class CreateUserPageTest extends TestCase
{
    use ResourcePage;
    use FilamentForm;
    use RefreshDatabase;

    protected function updateRecord() : void
    {
        $this->record = User::query()->latest()->first();
    }

    private function createGroup($id) : void {
        $group = new Group();
        $group->key = $id;
        $group->name = $id . '_name';
        $group->save();
    }

    protected function setUpPage(): void
    {
        User::query()->delete();
        $this->createGroup('K1');
        $this->createGroup('K2');
        $this->createGroup('K3');

        $this->submitAction = 'create';
        $this->modelClass = User::class;
        $this->url = UserResource::getUrl('create');
        $this->liveWireParameters = [];
        $this->liveWireClass = UserResource\Pages\CreateUser::class;
        $this->formDataSet = [
            'username' => fake()->company(),
            'name' => fake()->name(),
            'email' => fake()->email(),
            'language_iso' => fake()->randomElement(Language::query()->where('status', LanguageStatus::ACTIVE->value)->pluck('iso')),
            'role' => UserRole::NONE->value,
            'groups' => [fake()->randomElement(['K1', 'K2', 'K3'])],
            'two_factor' => fake()->randomElement(UserTwoFactor::cases())->value,
            'status' => fake()->randomElement(UserStatus::cases())->value,

        ];
    }

    public function testFieldUserNameIsRequired() {                         $this->seeIfFieldIsRequired('username'); }
    public function testFieldNameIsRequired() {                             $this->seeIfFieldIsRequired('name'); }
    public function testFieldEmailIsRequired() {                            $this->seeIfFieldIsRequired('email'); }
    public function testFieldLanguageIsoIsRequired() {                      $this->seeIfFieldIsRequired('language_iso'); }
    public function testFieldRoleIsRequired() {                             $this->seeIfFieldIsRequired('role'); }
    public function testFieldTwoFactorIsRequired() {                        $this->seeIfFieldIsRequired('two_factor'); }
    public function testFieldStatusIsRequired() {                           $this->seeIfFieldIsRequired('status'); }

    public function testFieldGroupsIsNotRequired() {                        $this->seeIfFieldIsNotRequired('groups'); }

    public function testFieldUserNameIsNotTooLong() {                       $this->seeIfFieldIsNotTooLong('username', 51); }
    public function testFieldNameIsNotTooLong() {                           $this->seeIfFieldIsNotTooLong('name', 256); }
    public function testFieldEmailIsNotTooLong() {                          $this->seeIfFieldIsNotTooLong('email', 256); }

    public function testFieldEmailMustBeAValidEmailAddress() {              $this->seeIfFieldIsValidatedBy('email', 'email', fake()->text(100)); }
    public function testFieldTwoFactorMustBeAValidTwoFactor() {             $this->seeIfFieldIsValidatedBy('two_factor', Enum::class, 'abc'); }
    public function testFieldStatusMustBeAValidStatus() {                   $this->seeIfFieldIsValidatedBy('status', Enum::class, 'abc'); }
    public function testFieldRoleMustBeAValidStatus() {                     $this->seeIfFieldIsValidatedBy('role', Enum::class, 'abc'); }

    public function testFieldUsernameCanBeUpdated() {                       $this->seeIfFieldIsUpdated('username'); }
    public function testFieldNameCanBeUpdated() {                           $this->seeIfFieldIsUpdated('name'); }
    public function testFieldEmailCanBeUpdated() {                          $this->seeIfFieldIsUpdated('email'); }
    public function testFieldLanguageIsoCanBeUpdated() {                    $this->seeIfFieldIsUpdated('language_iso'); }
    public function testFieldRoleCanBeUpdated() {                           $this->seeIfEnumFieldIsUpdated('role'); }
    public function testFieldTwoFactorCanBeUpdated() {                      $this->seeIfEnumFieldIsUpdated('two_factor'); }
    public function testFieldStatusCanBeUpdated() {                         $this->seeIfEnumFieldIsUpdated('status'); }

    public function testAfterSubmitUserIsCreated() {
        $this->actingAs($this->getActor());
        $this->setUpPage();

        $count = User::query()->count();
        $this->submitForm();

        $this->assertTrue(User::query()->count() === $count + 1);
        $this->updateRecord();
    }

    public function testAfterSubmitRedirectToViewUser() {
        $this->actingAs($this->getActor());
        $this->setUpPage();

        $this
            ->submitForm()
            ->assertRedirect(UserResource::getUrl('view', ['record' => \Mediamouse\Users\Models\User::query()->latest()->first()]));
    }

    public function testAfterCreateAnotherUserIsCreated() {
        $this->actingAs($this->getActor());
        $this->setUpPage();

        $count = User::query()->count();
        $this->submitAction = 'createAnother';
        $this->submitForm();

        $this->assertTrue(User::query()->count() === $count + 1);
    }

    public function testAfterCreateAnotherNoRedirection() {
        $this->actingAs($this->getActor());
        $this->setUpPage();
        $this->submitAction = 'createAnother';

        $this
            ->submitForm()
            ->assertNoRedirect();
    }

}

