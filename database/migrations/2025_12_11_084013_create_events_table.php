<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_by');
            $table->string('title', 200);
            $table->text('description');
            $table->date('event_date');
            $table->time('event_time');
            $table->string('location', 300)->nullable();
            $table->enum('event_type', ['online', 'physical', 'hybrid'])->default('physical');
            $table->string('category', 50);
            $table->enum('privacy', ['public', 'private'])->default('public');
            $table->integer('max_attendees')->nullable();
            $table->integer('attendee_count')->default(0);
            $table->string('event_image')->nullable();
            $table->string('meeting_link')->nullable();
            $table->enum('status', ['draft', 'published', 'cancelled', 'completed'])->default('published');
            $table->timestamps();
            
            $table->foreign('created_by')->references('id')->on('users_record')->onDelete('cascade');
            $table->index(['event_date', 'status']);
            $table->index('created_by');
        });
    }

    public function down()
    {
        Schema::dropIfExists('events');
    }
}