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
        Schema::table('chat_room_participants', function (Blueprint $table) {
            $table->timestamp('last_read_at')->nullable();
        });

        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropColumn(['read_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('chat_room_participants', function (Blueprint $table) {
            $table->dropColumn(['last_read_at']);
        });

        Schema::table('chat_messages', function (Blueprint $table) {
            $table->timestamp('read_at')->nullable();
        });
    }
};
