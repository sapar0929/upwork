<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('location_id')->index()->nullable()->constrained()->nullOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('avatar')->nullable();
            $table->string('username')->unique();
            $table->string('password');
            $table->double('rating')->default(0);
            $table->boolean('phone_number_verified')->default(0);
            $table->boolean('payment_method_verified')->default(0);
            $table->unsignedInteger('total_jobs')->default(0);
            $table->unsignedInteger('total_spent')->default(0);
            $table->json('previous_freelancers')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
