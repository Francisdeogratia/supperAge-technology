<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketplaceStoreViewsTable extends Migration
{
    public function up()
    {
        Schema::create('marketplace_store_views', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('viewer_id')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
            
            $table->foreign('store_id')->references('id')->on('marketplace_stores')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('marketplace_products')->onDelete('cascade');
            $table->foreign('viewer_id')->references('id')->on('users_record')->onDelete('set null');
            $table->index(['store_id', 'created_at']);
            $table->index(['product_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('marketplace_store_views');
    }
}