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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('property_id')
                ->constrained('properties');

            $table->foreignId('buyer_id')
                ->constrained('users');

            $table->foreignId('seller_id')
                ->constrained('users');

            $table->foreignId('agency_id')
                ->constrained('agencies');

            $table->string('type');

            $table->decimal('amount', 12, 2);

            // pending, in_progress, completed, cancelled
            $table->string('status')->default('pending');

            $table->dateTime('transaction_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
