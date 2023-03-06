<?php

namespace Feature\Resources\GroupResource;

use Filament\Forms\Components\Group;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mediamouse\Filament\Testing\Traits\FilamentForm;
use Mediamouse\Filament\Testing\Traits\ResourcePage;
use Mediamouse\Users\Filament\Resources\GroupResource;
use Tests\TestCase;

/**
 * @property Group record
 */
class CreateGroupPageTest extends TestCase
{
    use ResourcePage;
    use FilamentForm;
    use RefreshDatabase;

    protected function updateRecord(): void
    {
        $this->record = \Mediamouse\Users\Models\Group::query()->first();
    }

    protected function setUpPage(): void
    {
        $this->submitAction = 'create';
        $this->modelClass = Group::class;
        $this->url = GroupResource::getUrl('create');
        $this->liveWireParameters = [];
        $this->liveWireClass = GroupResource\Pages\CreateGroup::class;
        $this->formDataSet = [
            'key' => fake()->randomLetter() . fake()->randomLetter(),
            'name' => fake()->unique()->word(),
        ];
    }

    public function testFieldKeyIsRequired() {      $this->seeIfFieldIsRequired('key'); }
    public function testFieldNameIsRequired() {            $this->seeIfFieldIsRequired('name'); }

    public function testFieldKeyIsNotTooLong() {    $this->seeIfFieldIsNotToLong('key', 11); }
    public function testFieldNameIsNotTooLong() {          $this->seeIfFieldIsNotToLong('name', 101); }

    public function testFieldKeyMustBeAValidKey() {          $this->seeIfFieldIsValidatedBy('key', 'alphaNum', '@#$'); }
    public function testFieldNameMustBeAValidName() {     $this->seeIfFieldIsValidatedBy('name', 'alphaNum', '@#$'); }

    public function testFieldKeyCanBeUpdated() {    $this->seeIfFieldIsUpdated('key'); }
    public function testFieldNameCanBeUpdated() {          $this->seeIfFieldIsUpdated('name'); }

    public function testAfterSubmitGroupIsCreated() {
        $this->actingAs($this->getActor());
        $this->setUpPage();

        $this->submitForm();

        $this->assertTrue(\Mediamouse\Users\Models\Group::query()->count() === 1);
    }

    public function testAfterSubmitRedirectToViewGroup() {
        $this->actingAs($this->getActor());
        $this->setUpPage();

        $this
                ->submitForm()
                ->assertRedirect(GroupResource::getUrl('view', ['record' => \Mediamouse\Users\Models\Group::query()->first()]));
    }

    public function testAfterCreateAnotherGroupIsCreated() {
        $this->actingAs($this->getActor());
        $this->setUpPage();

        $this->submitAction = 'createAnother';
        $this->submitForm();

        $this->assertTrue(\Mediamouse\Users\Models\Group::query()->count() === 1);
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

