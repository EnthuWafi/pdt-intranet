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
        Schema::table('users', function (Blueprint $table) {
        $table->string('position')->nullable();
        $table->string('phone')->nullable();
        $table->foreignId('unit_id')->nullable()->constrained()->nullOnDelete();
        });

        Schema::table('units', function (Blueprint $table) {
        $table->string('name')->nullable();
        $table->foreignId('department_id')->nullable()->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['unit_id']); 
            $table->dropColumn(['position', 'phone', 'unit_id']);
        });

        Schema::table('units', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn(['name', 'department_id']);
        });
    }
};
