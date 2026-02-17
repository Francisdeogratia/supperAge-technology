<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketplaceStoresTable extends Migration
{
    public function up()
    {
        Schema::create('marketplace_stores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_id');
            $table->string('store_name');
            $table->string('store_slug')->unique();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('country')->default('NG');
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->enum('status', ['active', 'pending', 'suspended'])->default('active');
            $table->integer('total_products')->default(0);
            $table->integer('total_orders')->default(0);
            $table->integer('total_views')->default(0);
            $table->decimal('total_revenue', 15, 2)->default(0);
            $table->timestamps();
            
            $table->foreign('owner_id')->references('id')->on('users_record')->onDelete('cascade');
            $table->index(['status', 'created_at']);
            $table->index('store_slug');
        });
    }

    public function down()
    {
        Schema::dropIfExists('marketplace_stores');
    }
}