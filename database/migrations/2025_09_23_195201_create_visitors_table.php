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
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ip_address_id')->index()->constrained()->cascadeOnDelete();
            $table->foreignId('user_agent_id')->index()->constrained()->cascadeOnDelete();
            $table->unsignedInteger('hits')->default(1);
            $table->unsignedInteger('suspect_hits')->default(0);
            $table->boolean('robot')->default(0);
            $table->boolean('api')->default(0);
            $table->boolean('disabled')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
