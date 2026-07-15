<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_postings', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique()->nullable();
            $table->foreignId('group_company_id')->constrained()->restrictOnDelete();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('employment_type', 50)->nullable();
            $table->string('location')->nullable();
            $table->string('salary', 100)->nullable();
            $table->date('deadline')->nullable();
            $table->text('description')->nullable();
            $table->string('status', 50)->default('draft');
            $table->timestamp('approved_at')->nullable();
            $table->text('rejected_reason')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('created_by');
            $table->index(['group_company_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_postings');
    }
};
