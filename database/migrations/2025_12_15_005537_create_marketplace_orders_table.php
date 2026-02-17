<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketplaceOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('marketplace_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('buyer_id');
            
            // Buyer details
            $table->string('buyer_name');
            $table->string('buyer_email');
            $table->string('buyer_phone');
            $table->text('buyer_address');
            $table->string('buyer_city')->nullable();
            $table->string('buyer_state')->nullable();
            $table->string('buyer_country')->default('NG');
            
            // Order details
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 15, 2);
            $table->decimal('total_amount', 15, 2);
            $table->string('currency')->default('NGN');
            $table->text('notes')->nullable();
            
            // Status tracking
            $table->enum('status', ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            
            $table->timestamps();
            
            $table->foreign('store_id')->references('id')->on('marketplace_stores')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('marketplace_products')->onDelete('cascade');
            $table->foreign('buyer_id')->references('id')->on('users_record')->onDelete('cascade');
            $table->index(['store_id', 'status']);
            $table->index('order_number');
        });
    }

    public function down()
    {
        Schema::dropIfExists('marketplace_orders');
    }
}