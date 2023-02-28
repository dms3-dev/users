<?php

namespace Feature\Resources\Language;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mediamouse\Filament\Testing\Enums\ResourceType;
use Mediamouse\Filament\Testing\Traits\FilamentForm;
use Mediamouse\Filament\Testing\Traits\ResourcePage;
use Mediamouse\Users\Filament\Resources\LanguageResource\Pages\ListLanguages;
use Mediamouse\Users\Models\Language;
use Tests\TestCase;

/**
 * @property Language record
 */
class CreateLanguagePageTest extends TestCase
{
    use ResourcePage;
    use FilamentForm;
    use RefreshDatabase;

    protected function updateRecord() : void
    {
        $this->record = Language::query()->latest()->first();
    }

    protected function setUpPage(): void
    {
        Language::query()->delete();
        $this->type = ResourceType::PAGE_ACTION;
        $this->submitAction = 'create';
        $this->modelClass = Language::class;
        $this->liveWireParameters = [];
        $this->liveWireClass = ListLanguages::class;
        $this->formDataSet = [
            'iso' => fake()->unique()->languageCode(),
            'name' => fake()->unique()->firstName(),
        ];
    }

    public function testFieldIsoIsRequired() {                         $this->seeIfFieldIsRequired('iso'); }
    public function testFieldNameIsRequired() {                             $this->seeIfFieldIsRequired('name'); }

    public function testFieldIsoIsNotTooLong() {                       $this->seeIfFieldIsNotToLong('iso', 3); }
    public function testFieldNameIsNotTooLong() {                           $this->seeIfFieldIsNotToLong('name', 21); }

    public function testFieldIsoMustBeAValidIso() {              $this->seeIfFieldIsValidatedBy('iso', 'alpha','123'); }
    public function testFieldNameMustBeAValidName() {             $this->seeIfFieldIsValidatedBy('name','alpha','123'); }

    public function testFieldIsoCanBeUpdated() {                       $this->seeIfFieldIsUpdated('iso'); }
    public function testFieldNameCanBeUpdated() {                           $this->seeIfFieldIsUpdated('name'); }

    public function testAfterSubmitLanguageIsCreated() {
        $this->actingAs($this->getActor());
        $this->setUpPage();

        $this->submitForm();

        $this->assertTrue(Language::query()->count() === 1);
    }

    public function testAfterCreateAnotherLanguageIsCreated() {
        $this->actingAs($this->getActor());
        $this->setUpPage();

        $this->submitAction = 'createAnother';
        $this->submitForm();

        $this->assertTrue(Language::query()->count() === 1);
    }

}

