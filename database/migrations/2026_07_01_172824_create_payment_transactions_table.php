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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_order_id')->constrained();
            $table->string('transaction_reference')->unique();
            $table->decimal('amount',12,2)->nullable();
            $table->string('payment_method');
            $table->enum('status',['Approved','Rejected']);
            $table->string('response_code');
            $table->string('response_message');
            $table->dateTime('processed_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
