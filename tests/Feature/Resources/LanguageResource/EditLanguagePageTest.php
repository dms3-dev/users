<?php

namespace Feature\Resources\LanguageResource;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Mediamouse\Filament\Testing\Enums\ResourceType;
use Mediamouse\Filament\Testing\Traits\FilamentForm;
use Mediamouse\Filament\Testing\Traits\Resource;
use Mediamouse\Users\Enums\LanguageStatus;
use Mediamouse\Users\Filament\Resources\LanguageResource\Pages\ListLanguages;
use Mediamouse\Users\Models\Language;
use Tests\TestCase;

/**
 * @property Language record
 */
class EditLanguagePageTest extends TestCase
{
    use Resource;
    use FilamentForm;
    use RefreshDatabase;

    protected function updateRecord() : void
    {
        try {
            $this->record->refresh();
        }
        catch(\Throwable $e) {
            $this->record = Language::query()->where('iso', $this->formDataSet['iso'])->first();
        }
    }

    protected function setUpPage(): void
    {
        $this->type = ResourceType::TABLE_ACTION;
        $this->modelClass = Language::class;
        $this->record = Language::factory()->create();
        $this->record->status = LanguageStatus::ACTIVE;
        $this->liveWireParameters = ['record' => $this->record->iso];
        $this->liveWireClass = ListLanguages::class;

        $iso = fake()->unique()->languageCode();
        $this->formDataSet = [
            'iso' => $iso,
            'name' => $iso . 'name',
            'status' => fake()->randomElement(LanguageStatus::cases())->value,
        ];
    }


    public function testFieldIsoIsRequired() {                         $this->seeIfFieldIsRequired('iso'); }
    public function testFieldNameIsRequired() {                        $this->seeIfFieldIsRequired('name'); }

    public function testFieldIsoIsNotTooLong() {                       $this->seeIfFieldIsNotTooLong('iso', 3); }
    public function testFieldNameIsNotTooLong() {                      $this->seeIfFieldIsNotTooLong('name', 21); }

    public function testFieldIsoMustBeAValidIso() {               $this->seeIfFieldIsValidatedBy('iso', 'alpha','12'); }

    public function testFieldIsoCanBeUpdated() {                      $this->seeIfFieldIsUpdated('iso'); }
    public function testFieldNameCanBeUpdated() {                           $this->seeIfFieldIsUpdated('name'); }

}
