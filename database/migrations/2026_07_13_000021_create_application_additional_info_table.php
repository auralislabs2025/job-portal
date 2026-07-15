<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('application_additional_info', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->unique()->constrained()->cascadeOnDelete();
            $table->text('cover_letter')->nullable();
            $table->boolean('willing_to_relocate')->nullable();
            $table->boolean('willing_to_travel')->nullable();
            $table->string('interview_availability')->nullable();
            $table->string('linkedin_url', 500)->nullable();
            $table->string('portfolio_url', 500)->nullable();
            $table->string('reference_name')->nullable();
            $table->string('reference_contact', 50)->nullable();
            $table->text('additional_comments')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_additional_info');
    }
};
