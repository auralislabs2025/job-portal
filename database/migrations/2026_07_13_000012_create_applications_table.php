<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique()->nullable();
            $table->foreignId('job_posting_id')->constrained()->restrictOnDelete();
            $table->string('candidate_display_name')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile', 50)->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status', 50)->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->boolean('declared_information_accurate')->default(false);
            $table->boolean('declared_authorize_verification')->default(false);
            $table->boolean('declared_data_consent')->default(false);
            $table->timestamp('declared_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('email');
            $table->index('assigned_to');
            $table->index(['job_posting_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
