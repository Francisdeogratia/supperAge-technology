<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketplaceProductsTable extends Migration
{
    public function up()
    {
        Schema::create('marketplace_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id');
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->enum('type', ['product', 'service'])->default('product');
            $table->decimal('price', 15, 2);
            $table->string('currency')->default('NGN');
            $table->integer('stock')->default(0)->nullable();
            $table->json('images')->nullable();
            $table->string('category')->nullable();
            $table->enum('status', ['active', 'draft', 'out_of_stock'])->default('active');
            $table->integer('views')->default(0);
            $table->integer('orders')->default(0);
            $table->timestamps();
            
            $table->foreign('store_id')->references('id')->on('marketplace_stores')->onDelete('cascade');
            $table->index(['store_id', 'status']);
            $table->index('slug');
        });
    }

    public function down()
    {
        Schema::dropIfExists('marketplace_products');
    }
}