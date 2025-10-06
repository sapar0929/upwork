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
        Schema::create('works', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('client_id')->index()->constrained()->cascadeOnDelete();
            $table->foreignId('freelancer_id')->index()->nullable()->constrained()->nullOnDelete();
            $table->foreignId('profile_id')->index()->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('body');
            $table->unsignedTinyInteger('experience_level')->default(0);
            $table->unsignedTinyInteger('job_type')->default(0);
            $table->unsignedInteger('price')->default(0);
            $table->unsignedInteger('number_of_proposals')->default(0);
            $table->unsignedTinyInteger('project_type')->default(0);
            $table->unsignedTinyInteger('project_length')->default(0);
            $table->unsignedTinyInteger('hours_per_week')->default(0);
            $table->dateTime('last_viewed')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('works');
    }
};
