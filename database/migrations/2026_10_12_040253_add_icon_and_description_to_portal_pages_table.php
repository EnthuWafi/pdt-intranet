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
        Schema::table('portal_pages', function (Blueprint $table) {
            //
            $table->string('icon')->nullable()->default('heroicon-o-question-mark-circle');
            $table->text('description')->nullable();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('portal_pages', function (Blueprint $table) {
            //
            $table->dropColumn(['icon', 'description']);
        });
    }
};
