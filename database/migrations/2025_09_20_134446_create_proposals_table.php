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
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('work_id')->index()->constrained()->cascadeOnDelete();
            $table->foreignId('freelancer_id')->index()->constrained()->cascadeOnDelete();
            $table->foreignId('profile_id')->index()->constrained()->cascadeOnDelete();
            $table->text('cover_letter');
            $table->unsignedTinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
