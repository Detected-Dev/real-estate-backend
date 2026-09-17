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
        Schema::create('property_visits', function (Blueprint $table) {
            $table->id();

            $table->foreignId('property_id')
                ->constrained('properties');

            $table->foreignId('user_id')
                ->constrained('users');

            $table->foreignId('agency_id')
                ->constrained('agencies');

            $table->dateTime('scheduled_at');

            $table->text('message')->nullable();

            // pending, confirmed, completed, cancelled
            $table->string('status')->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_visits');
    }
};
