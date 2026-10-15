<?php

namespace Tests\Feature;

use App\Enums\ActionItemStatus;
use App\Enums\ActionType;
use App\Models\ActionItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActionItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_action_item()
    {
        $user = User::factory()->create();

        $actionItem = ActionItem::create([
            'title' => 'Test Action',
            'description' => 'Test Description',
            'action_type' => ActionType::REVIEW,
            'status' => ActionItemStatus::PENDING_APPROVAL,
            'assigned_to_user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('action_items', [
            'id' => $actionItem->id,
            'title' => 'Test Action',
            'status' => ActionItemStatus::PENDING_APPROVAL->value,
        ]);
    }

    public function test_user_can_claim_unassigned_action_item()
    {
        $user = User::factory()->create();

        $actionItem = ActionItem::create([
            'title' => 'Pool Action',
            'action_type' => ActionType::REVIEW,
            'status' => ActionItemStatus::PENDING_APPROVAL,
            'assigned_to_user_id' => null,
        ]);

        $claimed = $actionItem->claim($user);

        $this->assertTrue($claimed);
        $this->assertEquals($user->id, $actionItem->fresh()->assigned_to_user_id);
    }

    public function test_can_approve_action_item()
    {
        $user = User::factory()->create();

        $actionItem = ActionItem::create([
            'title' => 'Approval Task',
            'action_type' => ActionType::APPROVE,
            'status' => ActionItemStatus::PENDING_APPROVAL,
            'assigned_to_user_id' => $user->id,
        ]);

        $actionItem->approve($user, ['approved_field' => 'value'], 'Looks good');

        $fresh = $actionItem->fresh();
        $this->assertEquals(ActionItemStatus::COMPLETED, $fresh->status);
        $this->assertEquals($user->id, $fresh->completed_by_user_id);
        $this->assertEquals('value', $fresh->payload['approved_field'] ?? null);
    }

    public function test_can_reject_action_item()
    {
        $user = User::factory()->create();

        $actionItem = ActionItem::create([
            'title' => 'Reject Task',
            'action_type' => ActionType::APPROVE,
            'status' => ActionItemStatus::PENDING_APPROVAL,
            'assigned_to_user_id' => $user->id,
        ]);

        $actionItem->reject($user, 'Not acceptable');

        $fresh = $actionItem->fresh();
        $this->assertEquals(ActionItemStatus::CANCELLED, $fresh->status);
        $this->assertEquals('Not acceptable', $fresh->review_notes);
    }

    public function test_can_delegate_action_item()
    {
        $manager = User::factory()->create();
        $subordinate = User::factory()->create(['manager_id' => $manager->id]);

        $actionItem = ActionItem::create([
            'title' => 'Root Task',
            'action_type' => ActionType::APPROVE,
            'status' => ActionItemStatus::PENDING_APPROVAL,
            'assigned_to_user_id' => $manager->id,
        ]);

        $child = $actionItem->delegate($manager, $subordinate, 'Please review');

        $this->assertEquals(ActionItemStatus::COMPLETED, $actionItem->fresh()->status);
        $this->assertEquals($subordinate->id, $child->assigned_to_user_id);
        $this->assertEquals(ActionType::REVIEW, $child->action_type);
        $this->assertEquals(ActionItemStatus::PENDING_APPROVAL, $child->status);
    }

    public function test_can_submit_review_and_spawn_approval()
    {
        $manager = User::factory()->create();
        $subordinate = User::factory()->create(['manager_id' => $manager->id]);

        $actionItem = ActionItem::create([
            'title' => 'Review Task',
            'action_type' => ActionType::REVIEW,
            'status' => ActionItemStatus::PENDING_APPROVAL,
            'assigned_to_user_id' => $subordinate->id,
            'assigned_by_user_id' => $manager->id,
        ]);

        $approvalTask = $actionItem->submitReview($subordinate, ['data' => 123], 'Done reviewing');

        $this->assertEquals(ActionItemStatus::COMPLETED, $actionItem->fresh()->status);
        $this->assertEquals($manager->id, $approvalTask->assigned_to_user_id);
        $this->assertEquals(ActionType::APPROVE, $approvalTask->action_type);
        $this->assertEquals(123, $approvalTask->payload['data'] ?? null);
    }

    public function test_can_return_for_revision()
    {
        $manager = User::factory()->create();
        $subordinate = User::factory()->create(['manager_id' => $manager->id]);

        $actionItem = ActionItem::create([
            'title' => 'Approval Sign-off',
            'action_type' => ActionType::APPROVE,
            'status' => ActionItemStatus::PENDING_APPROVAL,
            'assigned_to_user_id' => $manager->id,
            'assigned_by_user_id' => $subordinate->id,
        ]);

        $revisionTask = $actionItem->returnForRevision($manager, 'Needs fixes');

        $this->assertEquals(ActionItemStatus::CANCELLED, $actionItem->fresh()->status);
        $this->assertEquals($subordinate->id, $revisionTask->assigned_to_user_id);
        $this->assertEquals(ActionType::REVISE, $revisionTask->action_type);
    }

    public function test_can_reassign_action_item()
    {
        $actor = User::factory()->create();
        $oldAssignee = User::factory()->create();
        $newAssignee = User::factory()->create();

        $actionItem = ActionItem::create([
            'title' => 'Task',
            'action_type' => ActionType::REVIEW,
            'status' => ActionItemStatus::PENDING_APPROVAL,
            'assigned_to_user_id' => $oldAssignee->id,
        ]);

        $successor = $actionItem->reassign($actor, $newAssignee, 'Workload balancing');

        $this->assertEquals(ActionItemStatus::CANCELLED, $actionItem->fresh()->status);
        $this->assertEquals($newAssignee->id, $successor->assigned_to_user_id);
    }
}
