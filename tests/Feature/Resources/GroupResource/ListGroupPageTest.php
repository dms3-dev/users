<?php

namespace Feature\Resources\GroupResource;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mediamouse\Filament\Testing\Traits\FilamentTable;
use Mediamouse\Filament\Testing\Traits\ResourcePage;
use Mediamouse\Users\Filament\Resources\GroupResource;
use Mediamouse\Users\Filament\Resources\GroupResource\Pages\ListGroups;
use Mediamouse\Users\Models\Group;
use Mediamouse\Users\Models\User;
use Mediamouse\Users\Models\UserMemberOfGroup;
use Tests\TestCase;

class ListGroupPageTest extends TestCase
{
    use ResourcePage;
    use FilamentTable;
    use RefreshDatabase;

    protected function setUpPage($count = 10): void
    {
        for($i = 0; $i < $count; $i++) {
            $record = new Group();
            $record->created_at = Carbon::now()->subYears(5)->addDays($i);
            $record->key = fake()->unique()->regexify('[A-Z]{3}[0-9]{3}');
            $record->name = fake()->unique()->word();
            $record->save();

            for($j = 0; $j < (($i + 5) % 10); $j++) {
                /** @var User $user */
                $user = \App\Models\User::factory()->create();
                $memberof = new UserMemberOfGroup();

                $memberof->user_id = $user->id;
                $memberof->group_key = $record->key;
                $memberof->save();
            }
        }

        $this->liveWireClass = ListGroups::class;
        $this->url = GroupResource::getUrl();
        $this->records = Group::all();
        $this->query = Group::query();
        $this->secondSortingColumn = 'key';
    }

    protected function getResourceUrlForAction($action, $record): string
    {
        return GroupResource::getUrl($action, ['record' => $record]);
    }

    public function testTheColumnKeyIsVisibleByDefault() {                   $this->seeIfColumnIsVisibleByDefault('key'); }
    public function testTheColumnNameIsVisibleByDefault() {          $this->seeIfColumnIsVisibleByDefault('name'); }
    public function testTheColumnAmountOfUsersVisibleByDefault() {          $this->seeIfColumnIsVisibleByDefault('users_count'); }
    public function testTheColumnCreatedAtIsVisibleByDefault() {          $this->seeIfColumnIsVisibleByDefault('created_at'); }

    public function testTheColumnKeyIsSortable() {           $this->seeIfColumnIsSortable('key'); }
    public function testTheColumnNameIsSortable() {  $this->seeIfColumnIsSortable('name'); }
    public function testTheColumnCreatedAtIsSortable() {  $this->seeIfColumnIsSortable('created_at'); }

    public function testTheColumnKeyIsSortableDesc() {           $this->seeIfColumnIsSortableDesc('key'); }
    public function testTheColumnNameNameIsSortableDesc() {  $this->seeIfColumnIsSortableDesc('name'); }
    public function testTheColumnCreatedAtIsSortableDesc() {  $this->seeIfColumnIsSortableDesc('created_at'); }

    public function testTheColumnKeyIsSearchable() {             $this->seeIfColumnIsSearchable('key'); }
    public function testTheColumnNameIsSearchable() {    $this->seeIfColumnIsSearchable('name'); }

    public function testNewGroupActionExists() {
        $this->seeIfPageTableHasAction('create');
    }

//    public function testNewGroupActionLeadsToNewGroupPage() {
//        $this->seeIfPageTableActionLinksTo('create', GroupResource::getUrl('create'));
//    }

    public function testViewActionExists() {
        $this->seeIfTableHasAction('view');
        $this->renderTable(1)->assertTableActionExists('view');
    }

    public function testViewActionLinksToViewGroup() {
        $this->seeIfTableActionLinksToUrl('view');
    }

//    public function testEditActionExists() {
//        $this->seeIfTableHasAction('edit');
//    }

    public function testDeleteActionExists() {
        $this->seeIfTableHasAction('delete');
    }
}
