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
        Schema::create('auth_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ip_address_id')->index()->constrained()->cascadeOnDelete();
            $table->foreignId('user_agent_id')->index()->constrained()->cascadeOnDelete();
            $table->string('username');
            $table->string('event');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auth_attempts');
    }
};
