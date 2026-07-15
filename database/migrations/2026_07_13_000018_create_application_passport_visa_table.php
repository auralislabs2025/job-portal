<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('application_passport_visa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('passport_number', 50)->nullable();
            $table->date('passport_expiry_date')->nullable();
            $table->string('passport_issuing_country', 100)->nullable();
            $table->string('visa_status', 50)->nullable();
            $table->string('visa_type', 100)->nullable();
            $table->date('visa_expiry_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_passport_visa');
    }
};
