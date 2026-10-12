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
        Schema::table('portal_pages', function (Blueprint $table) {
            $table->dropColumn(['content', 'metadata', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('portal_pages', function (Blueprint $table) {
            $table->longText('content')->nullable();
            $table->json('metadata')->nullable();
            $table->boolean('is_active')->default(true);
        });
    }
};
