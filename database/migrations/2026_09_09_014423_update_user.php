<?php

use App\Enums\UserStatus;
use App\Enums\UserCategory;
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
        Schema::table("users", function(Blueprint $table) {
            $table->string('user_category')->default(UserCategory::INTERNAL->value);
            $table->string('agency_name')->nullable();
            $table->string('status')->default(UserStatus::PENDING->value);
            $table->timestamp('last_login_at')->nullable();
            $table->string('avatar_url')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table("users", function(Blueprint $table) {
            $table->dropColumn('user_category');
            $table->dropColumn('agency_name');
            $table->dropColumn('status');
            $table->dropColumn('last_login_at');
            $table->dropColumn('avatar_url');
        });
        
    }
};
