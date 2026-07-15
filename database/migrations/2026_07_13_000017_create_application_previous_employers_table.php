<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('application_previous_employers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->string('employer_name')->nullable();
            $table->string('designation')->nullable();
            $table->string('industry', 100)->nullable();
            $table->text('responsibilities')->nullable();
            $table->date('started_on')->nullable();
            $table->date('ended_on')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('application_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_previous_employers');
    }
};
