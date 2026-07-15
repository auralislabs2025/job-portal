<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('application_employment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->unique()->constrained()->cascadeOnDelete();
            $table->decimal('total_years_experience', 4, 1)->nullable();
            $table->string('current_designation')->nullable();
            $table->string('industry', 100)->nullable();
            $table->text('key_responsibilities')->nullable();
            $table->text('relevant_experience')->nullable();
            $table->date('last_working_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_employment');
    }
};
