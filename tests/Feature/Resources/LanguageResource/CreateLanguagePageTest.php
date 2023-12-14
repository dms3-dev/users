<?php

namespace Feature\Resources\LanguageResource;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mediamouse\Filament\Testing\Enums\ResourceType;
use Mediamouse\Filament\Testing\Traits\FilamentForm;
use Mediamouse\Filament\Testing\Traits\ResourcePage;
use Mediamouse\Users\Enums\LanguageStatus;
use Mediamouse\Users\Filament\Resources\LanguageResource;
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
        $this->type = ResourceType::PAGE_ACTION;
        $this->url = LanguageResource::getUrl();
        $this->submitAction = 'create';
        $this->modelClass = Language::class;
        $this->liveWireParameters = [];
        $this->liveWireClass = ListLanguages::class;
        $this->formDataSet = [
            'iso' => fake()->unique()->languageCode(),
            'name' => fake()->unique()->firstName(),
            'status' => fake()->randomElement(LanguageStatus::cases())->value
        ];
    }

    public function testFieldIsoIsRequired() {          $this->seeIfFieldIsRequired('iso'); }
    public function testFieldNameIsRequired() {         $this->seeIfFieldIsRequired('name'); }

    public function testFieldIsoIsNotTooLong() {        $this->seeIfFieldIsNotTooLong('iso', 3); }
    public function testFieldNameIsNotTooLong() {       $this->seeIfFieldIsNotTooLong('name', 21); }

    public function testFieldIsoMustBeAValidIso() {              $this->seeIfFieldIsValidatedBy('iso', 'alpha','123'); }

    public function testFieldIsoCanBeUpdated() {                       $this->seeIfFieldIsUpdated('iso'); }
    public function testFieldNameCanBeUpdated() {                           $this->seeIfFieldIsUpdated('name'); }

    public function testAfterSubmitLanguageIsCreated() {
        $this->actingAs($this->getActor());
        $this->setUpPage();

        $count = Language::query()->count();
        $this->submitForm();

        $this->assertTrue(Language::query()->count() === $count +  1);
    }

    public function testAfterCreateAnotherLanguageIsCreated() {
        $this->actingAs($this->getActor());
        $this->setUpPage();

        $count = Language::query()->count();
        $this->submitAction = 'createAnother';
        $this->submitForm();

        $this->assertTrue(Language::query()->count() === $count + 1);
    }

}

