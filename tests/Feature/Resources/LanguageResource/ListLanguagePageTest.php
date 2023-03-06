<?php

namespace Feature\Resources\LanguageResource;

use App\Models\User;
use Mediamouse\Filament\Testing\Traits\FilamentTable;
use Mediamouse\Filament\Testing\Traits\ResourcePage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mediamouse\Users\Filament\Resources\LanguageResource;
use Mediamouse\Users\Filament\Resources\LanguageResource\Pages\ListLanguages;
use Mediamouse\Users\Filament\Resources\UserResource;
use Mediamouse\Users\Models\Language;
use Tests\TestCase;

class ListLanguagePageTest extends TestCase
{
    use ResourcePage;
    use FilamentTable;
    use RefreshDatabase;

    protected function setUpPage($count = 10): void
    {
        $this->records = Language::factory()->count($count)->create();

        $this->liveWireClass = ListLanguages::class;
        $this->url = LanguageResource::getUrl();
        $this->records = Language::all();
        $this->query = Language::query();

        $i = 0;
        /** @var Language $language */
        foreach(Language::all() as $language) {
            User::factory()->count(($i + 5) % 10)->create([
                'language_iso' => $language->iso
            ]);

            $i++;
        }

    }

    protected function getResourceUrlForAction($action, $record) : string {
        return LanguageResource::getUrl($action, ['record' => $record]);
    }


    public function testTheColumnIsoIsVisibleByDefault() {             $this->seeIfColumnIsVisibleByDefault('iso'); }
    public function testTheColumnNameIsVisibleByDefault() {                 $this->seeIfColumnIsVisibleByDefault('name'); }
    public function testTheColumnStatusIsVisibleByDefault() {                $this->seeIfColumnIsVisibleByDefault('status'); }
    public function testTheColumnUsersCountIsVisibleByDefault() {                 $this->seeIfColumnIsVisibleByDefault('users_count'); }

    public function testTheColumnIsoIsSortable() {                     $this->seeIfColumnIsSortable('iso'); }
    public function testTheColumnNameIsSortable() {                         $this->seeIfColumnIsSortable('name'); }
    public function testTheColumnStatusIsSortable() {                        $this->seeIfColumnIsSortable('status'); }

    public function testTheColumnIsoIsSortableDesc() {                 $this->seeIfColumnIsSortableDesc('iso'); }
    public function testTheColumnNameIsSortableDesc() {                     $this->seeIfColumnIsSortableDesc('name'); }
    public function testTheColumnStatusIsSortableDesc() {                    $this->seeIfColumnIsSortableDesc('status'); }

    public function testTheColumnIsoIsSearchable() {                   $this->seeIfColumnIsSearchable('iso'); }
    public function testTheColumnNameIsSearchable() {                       $this->seeIfColumnIsSearchable('name'); }

    public function testNewLanguageActionExists() {
        $this->seeIfPageTableHasAction('create');
    }

    public function testEditActionExists() {
        $this->seeIfTableHasAction('edit');
    }

    public function testDeleteActionExists() {
        $this->seeIfTableHasAction('delete');
    }
}
