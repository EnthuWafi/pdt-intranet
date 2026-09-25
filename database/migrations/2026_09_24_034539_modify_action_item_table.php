<?php

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
        //
        Schema::table('action_items', function (Blueprint $table) {
            // Holds proposed field changes
            $table->json('payload')->nullable()->after('description');
            
            // Feedback if the manager sends it back or rejects
            $table->text('review_notes')->nullable()->after('payload');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('action_items', function (Blueprint $table) {
            $table->dropColumn(['payload', 'review_notes']);
        });
    }
};
