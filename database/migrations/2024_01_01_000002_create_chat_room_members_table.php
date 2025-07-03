<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('chat_room_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_room_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamp('last_read_at')->nullable();
            $table->enum('role', ['member', 'admin', 'moderator'])->default('member');
            $table->timestamps();

            $table->unique(['chat_room_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('chat_room_members');
    }
};
