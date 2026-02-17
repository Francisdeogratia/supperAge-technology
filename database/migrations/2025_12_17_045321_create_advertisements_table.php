<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertisementsTable extends Migration
{
    public function up()
    {
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('advertiser_id');
            $table->string('title');
            $table->text('description');
            $table->string('ad_type'); // banner, sponsored_post, video
            $table->string('media_url')->nullable();
            $table->string('media_type')->nullable(); // image, video
            $table->string('cta_text')->default('Learn More');
            $table->string('cta_link');
            $table->enum('status', ['draft', 'pending', 'active', 'paused', 'completed', 'rejected'])->default('draft');
            
            // Targeting
            $table->json('target_countries')->nullable();
            $table->json('target_age_range')->nullable();
            $table->json('target_gender')->nullable();
            $table->json('target_interests')->nullable();
            
            // Budget & Schedule
            $table->decimal('budget', 10, 2);
            $table->string('currency')->default('NGN');
            $table->date('start_date');
            $table->date('end_date');
            
            // Performance Metrics
            $table->integer('impressions')->default(0);
            $table->integer('clicks')->default(0);
            $table->integer('conversions')->default(0);
            $table->decimal('spent', 10, 2)->default(0);
            
            // Pricing
            $table->decimal('cost_per_click', 10, 2)->default(0);
            $table->decimal('cost_per_impression', 10, 4)->default(0);
            
            $table->timestamps();
            
            $table->foreign('advertiser_id')->references('id')->on('users_record')->onDelete('cascade');
            $table->index(['status', 'start_date', 'end_date']);
        });

        Schema::create('ad_impressions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ad_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('ip_address');
            $table->string('user_agent')->nullable();
            $table->string('country')->nullable();
            $table->string('device_type')->nullable();
            $table->timestamp('viewed_at');
            
            $table->foreign('ad_id')->references('id')->on('advertisements')->onDelete('cascade');
            $table->index(['ad_id', 'viewed_at']);
        });

        Schema::create('ad_clicks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ad_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('ip_address');
            $table->string('user_agent')->nullable();
            $table->string('country')->nullable();
            $table->boolean('converted')->default(false);
            $table->timestamp('clicked_at');
            
            $table->foreign('ad_id')->references('id')->on('advertisements')->onDelete('cascade');
            $table->index(['ad_id', 'clicked_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('ad_clicks');
        Schema::dropIfExists('ad_impressions');
        Schema::dropIfExists('advertisements');
    }
}