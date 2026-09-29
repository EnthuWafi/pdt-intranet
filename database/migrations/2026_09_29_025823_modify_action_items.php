<?php

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
        //
        Schema::table('action_items', function (Blueprint $table) {
            $table->string('action_type')->default(ActionType::APPROVE)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('action_items', function (Blueprint $table) {
            $table->string('action_type')->default('view')->change();
        });
    }
};
