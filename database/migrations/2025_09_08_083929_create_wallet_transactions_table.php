

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();

            // The user whose wallet is being credited/debited/funded
            $table->unsignedBigInteger('wallet_owner_id');

            // The user who initiated the payment (nullable for system rewards)// the user who paid
            $table->unsignedBigInteger('payer_id')->nullable();

            // Unique identifiers
            $table->string('transaction_id')->unique();   // always required
            $table->string('tx_ref')->nullable();         // optional external reference

            // Transaction details
            $table->decimal('amount', 10, 2);
            $table->string('status');                     // e.g. 'successful', 'pending', 'failed'
            $table->string('type')->default('general');         // e.g. 'task_reward', 'transfer', etc.
            $table->text('description')->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('wallet_owner_id')
                  ->references('id')->on('users_record')
                  ->onDelete('cascade');

            $table->foreign('payer_id')
                  ->references('id')->on('users_record')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
