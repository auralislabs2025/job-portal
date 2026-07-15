<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('application_job_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('position_applying_for')->nullable();
            $table->string('preferred_job_category', 100)->nullable();
            $table->string('current_job_title')->nullable();
            $table->string('current_employer')->nullable();
            $table->string('employment_status', 50)->nullable();
            $table->string('notice_period', 50)->nullable();
            $table->date('available_to_join_from')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_job_details');
    }
};
