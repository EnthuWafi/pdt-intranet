<?php

namespace Tests\Feature;

use App\Filament\Resources\ActionItems\Pages\ListActionItems;
use App\Filament\Resources\ActionItems\Pages\ViewActionItem;
use App\Filament\Resources\ActionItems\ActionItemResource;
use App\Models\ActionItem;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ActionItemResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Permission::firstOrCreate(['name' => 'ViewAny:ActionItem']);
        Permission::firstOrCreate(['name' => 'View:ActionItem']);
    }

    public function test_can_render_action_item_index_page()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('ViewAny:ActionItem');

        $this->actingAs($user);

        Livewire::test(ListActionItems::class)
            ->assertSuccessful();
    }

    public function test_navigation_badge_displays_correctly()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('ViewAny:ActionItem');

        ActionItem::create([
            'title' => 'Pending Task',
            'assigned_to_user_id' => $user->id,
            'status' => \App\Enums\ActionItemStatus::PENDING_APPROVAL,
            'action_type' => \App\Enums\ActionType::REVIEW,
        ]);

        $this->actingAs($user);

        $badge = ActionItemResource::getNavigationBadge();
        $this->assertEquals('1', $badge);
    }
}
