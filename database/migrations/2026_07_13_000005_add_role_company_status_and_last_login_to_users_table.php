<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->after('password')->constrained()->restrictOnDelete();
            $table->foreignId('group_company_id')->after('role_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status', 50)->after('group_company_id')->default('active');
            $table->timestamp('last_login_at')->after('remember_token')->nullable();

            $table->index('role_id');
            $table->index('group_company_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropForeign(['group_company_id']);
            $table->dropIndex(['role_id']);
            $table->dropIndex(['group_company_id']);
            $table->dropIndex(['status']);
            $table->dropColumn(['role_id', 'group_company_id', 'status', 'last_login_at']);
        });
    }
};
