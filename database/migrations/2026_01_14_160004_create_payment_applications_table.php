<?php

// Migration for payment_applications table
// Create file: database/migrations/2026_01_14_create_payment_applications_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentApplicationsTable extends Migration
{
    public function up()
    {
        Schema::create('payment_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('account_name');
            $table->decimal('amount_requested', 15, 2);
            $table->string('currency', 10)->default('NGN');
            $table->enum('payment_method', ['bank_transfer', 'paypal', 'flutterwave'])->default('bank_transfer');
            $table->string('paypal_email')->nullable();
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'paid', 'rejected'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->unsignedBigInteger('paid_by')->nullable();
            $table->text('admin_note')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users_record')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_applications');
    }
}