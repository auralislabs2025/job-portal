<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_company_id')->constrained()->restrictOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('group_company_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_groups');
    }
};
