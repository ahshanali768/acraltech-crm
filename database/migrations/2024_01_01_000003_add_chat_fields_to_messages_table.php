<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('messages', function (Blueprint $table) {
            // Add new columns for enhanced chat functionality
            $table->foreignId('chat_room_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('message_type', ['text', 'image', 'file', 'system'])->default('text');
            $table->string('attachment_url')->nullable();
            $table->boolean('is_read')->default(false);
            $table->foreignId('reply_to_id')->nullable()->constrained('messages')->onDelete('set null');
            
            // Add index for better performance
            $table->index(['chat_room_id', 'created_at']);
            $table->index(['sender_id', 'receiver_id']);
            $table->index(['is_read']);
        });
    }

    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['chat_room_id']);
            $table->dropForeign(['reply_to_id']);
            $table->dropColumn([
                'chat_room_id',
                'message_type', 
                'attachment_url',
                'is_read',
                'reply_to_id'
            ]);
        });
    }
};
