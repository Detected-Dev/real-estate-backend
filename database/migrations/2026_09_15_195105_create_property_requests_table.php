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
        Schema::create('property_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('property_id')
                ->constrained('properties');

            $table->foreignId('user_id')
                ->constrained('users');

            $table->foreignId('agency_id')
                ->constrained('agencies');

            $table->string('type');

            $table->text('message')->nullable();

            $table->decimal('offer_price', 12, 2)->nullable();

            // pending, accepted, rejected, cancelled, completed
            $table->string('status')->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_requests');
    }
};
