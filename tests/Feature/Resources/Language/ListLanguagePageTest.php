<?php

namespace Feature\Resources\Language;

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
        $this->records = Language::factory()->count($count - Language::query()->count())->create();

        $this->liveWireClass = ListLanguages::class;
        $this->url = LanguageResource::getUrl();
        $this->records = Language::all();
        $this->query = Language::query();
    }

    protected function getResourceUrlForAction($action, $record) : string {
        return LanguageResource::getUrl($action, ['record' => $record]);
    }


    public function testTheColumnIsoIsVisibleByDefault() {             $this->seeIfColumnIsVisibleByDefault('iso'); }
    public function testTheColumnNameIsVisibleByDefault() {                 $this->seeIfColumnIsVisibleByDefault('name'); }
    public function testTheColumnStatusIsVisibleByDefault() {                $this->seeIfColumnIsVisibleByDefault('status'); }
    public function testTheColumnUsersCountIsVisibleByDefault() {                 $this->seeIfColumnIsVisibleByDefault('users_count'); }

    public function testTheColumnUserNameIsSortable() {                     $this->seeIfColumnIsSortable('iso'); }
    public function testTheColumnNameIsSortable() {                         $this->seeIfColumnIsSortable('name'); }
    public function testTheColumnStatusIsSortable() {                        $this->seeIfColumnIsSortable('status'); }
    public function testTheColumnUsersCountVerifiedAttIsSortable() {             $this->seeIfColumnIsSortable('users_count'); }

    public function testTheColumnUserNameIsSortableDesc() {                 $this->seeIfColumnIsSortableDesc('iso'); }
    public function testTheColumnNameIsSortableDesc() {                     $this->seeIfColumnIsSortableDesc('name'); }
    public function testTheColumnStatusIsSortableDesc() {                    $this->seeIfColumnIsSortableDesc('status'); }
    public function testTheColumnUsersCountVerifiedAttIsSortableDesc() {         $this->seeIfColumnIsSortableDesc('users_count'); }

    public function testTheColumnUserNameIsSearchable() {                   $this->seeIfColumnIsSearchable('iso'); }
    public function testTheColumnNameIsSearchable() {                       $this->seeIfColumnIsSearchable('name'); }
    public function testTheColumnStatusIsSearchable() {                      $this->seeIfColumnIsSearchable('status'); }
    public function testTheColumnUsersCountIsSearchable() {                       $this->seeIfColumnIsSearchable('users_count'); }

    public function testNewUserActionExists() {
        $this->seeIfPageTableHasAction('create');
    }

    public function testNewUserActionLeadsToNewUserPage() {
        $this->seeIfPageTableActionLinksTo('create', UserResource::getUrl('create'));
    }

    public function testViewActionLinksToViewUser() {
        $this->seeIfTableActionLinksToUrl('view');
    }
}
