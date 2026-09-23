<?php

use App\Enums\ActionItemStatus;
use App\Enums\ActionType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('action_items', function (Blueprint $table) {
            $table->id();

            // Assignment: Direct user assignment OR permission/role pool
            $table->foreignId('assigned_to_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('assigned_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
                
            // Matches Spatie permission string (e.g., 'Update:ModuleRequest')
            $table->json('required_permission')->nullable();

            // Polymorphic target (ModuleRequest, AssetLoan, etc.)
            $table->morphs('actionable');

            // Metadata
            $table->string('title');
            $table->text('description')->nullable();


            $table->string('action_type')->default(ActionType::VIEW);
            $table->string('status')->default(ActionItemStatus::PENDING_APPROVAL->value);

            // Resolution audit trail
            $table->foreignId('completed_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

            // Indexes for inbox query performance
            $table->index(['status', 'assigned_to_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('action_items');
    }
};
