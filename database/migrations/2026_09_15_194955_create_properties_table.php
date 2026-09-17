<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();

            $table->foreignId('owner_id')
                ->constrained('users');

            $table->foreignId('agency_id')
                ->constrained('agencies');

            $table->foreignId('property_type_id')
                ->constrained('property_types');

            $table->string('title');
            $table->text('description')->nullable();

            $table->string('transaction_type');

            $table->decimal('price', 12, 2);

            $table->string('address');
            $table->string('city');
            $table->string('postal_code')->nullable();

            $table->decimal('surface', 10, 2);

            $table->unsignedInteger('bedrooms')->nullable();
            $table->unsignedInteger('bathrooms')->nullable();
            $table->unsignedInteger('floors')->nullable();

            $table->string('status')->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
