<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('application_educations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_highest')->nullable();
            $table->string('qualification_level', 100)->nullable();
            $table->string('degree_diploma')->nullable();
            $table->string('specialization')->nullable();
            $table->string('institution')->nullable();
            $table->integer('graduation_year')->nullable();
            $table->string('grade_percentage', 50)->nullable();
            $table->timestamps();

            $table->index('application_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_educations');
    }
};
