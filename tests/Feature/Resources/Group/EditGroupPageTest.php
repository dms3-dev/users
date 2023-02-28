<?php

namespace Feature\Resources\Group;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mediamouse\Filament\Testing\Traits\FilamentForm;
use Mediamouse\Filament\Testing\Traits\ResourcePage;
use Mediamouse\Users\Filament\Resources\GroupResource;
use Mediamouse\Users\Models\Group;
use Tests\TestCase;

/**
 * @property Group record
 */
class EditGroupPageTest extends TestCase
{
    use ResourcePage;
    use FilamentForm;
    use RefreshDatabase;

    protected function setUpPage(): void
    {
        $this->record = new Group();
        $this->record->key = 'G1';
        $this->record->name = 'G1L';
        $this->record->save();
        $this->modelClass = Group::class;
        $this->url = GroupResource::getUrl('edit', ['record' => $this->record]);
        $this->liveWireParameters = ['record' => $this->record->key];
        $this->liveWireClass = GroupResource\Pages\EditGroup::class;
        $this->formDataSet = [
            'key' => fake()->randomLetter() . fake()->randomNumber() . fake()->randomLetter(),
            'name' => fake()->unique()->word(),
        ];
    }

    public function testFieldKeyIsRequired() {      $this->seeIfFieldIsRequired('key'); }
    public function testFieldNameIsRequired() {            $this->seeIfFieldIsRequired('name'); }

    public function testFieldKeyIsNotTooLong() {    $this->seeIfFieldIsNotToLong('key', 11); }
    public function testFieldNameIsNotTooLong() {          $this->seeIfFieldIsNotToLong('name', 101); }

    public function testFieldKeyMustBeAValidKey() {          $this->seeIfFieldIsValidatedBy('key', 'alphaNum', '@$%'); } // Werkt niet
    public function testFieldNameMustBeAValidName() {     $this->seeIfFieldIsValidatedBy('name', 'alphaNum', '@$%'); } // Werkt niet

//    public function testFieldKeyCanBeUpdated() {    $this->seeIfFieldIsUpdated('key'); } // Werkt niet
//    public function testFieldNameCanBeUpdated() {          $this->seeIfFieldIsUpdated('name'); } // Werkt niet


    public function testAfterSubmitRedirectToViewGroup() {

        $this->actingAs($this->getActor());
        $this->setUpPage();

        $liveWire = $this->submitForm();
        $url = str_replace('G1', $this->formDataSet['key'], GroupResource::getUrl('view', ['record' => $this->record]));
        GroupResource::getUrl('view', ['record' => $this->record]);
            $liveWire->assertRedirect($url);
    }
}
