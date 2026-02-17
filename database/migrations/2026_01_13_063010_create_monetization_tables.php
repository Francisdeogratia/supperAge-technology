<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // User monetization status table
        Schema::create('user_monetization', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('status', ['pending', 'approved', 'rejected', 'suspended'])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('total_earnings', 10, 2)->default(0);
            $table->timestamps();
            
            // Only add foreign key if users_record table exists
            if (Schema::hasTable('users_record')) {
                $table->foreign('user_id')->references('id')->on('users_record')->onDelete('cascade');
            }
        });
        
        // Post performance tracking
        Schema::create('post_performance_tracking', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('user_id');
            $table->integer('likes_count')->default(0);
            $table->integer('comments_count')->default(0);
            $table->integer('reposts_count')->default(0);
            $table->integer('shares_count')->default(0);
            $table->integer('views_count')->default(0);
            $table->timestamp('fire_notification_sent_at')->nullable();
            $table->boolean('is_viral')->default(false);
            $table->timestamp('viral_achieved_at')->nullable();
            $table->timestamps();
            
            // Add indexes first
            $table->index('post_id');
            $table->index('user_id');
        });
        
        // Admin actions log
        Schema::create('monetization_actions_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('action', ['approved', 'rejected', 'suspended', 'message_sent']);
            $table->text('message')->nullable();
            $table->timestamps();
            
            // Add indexes
            $table->index('admin_id');
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('monetization_actions_log');
        Schema::dropIfExists('post_performance_tracking');
        Schema::dropIfExists('user_monetization');
    }
};