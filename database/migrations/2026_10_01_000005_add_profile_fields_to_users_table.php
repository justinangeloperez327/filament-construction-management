<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('employee_number', 64)->nullable()->unique()->after('name');
            $table->string('phone', 64)->nullable()->after('email');
            $table->foreignId('department_id')->nullable()->after('password')->constrained()->nullOnDelete();
            $table->foreignId('designation_id')->nullable()->after('department_id')->constrained()->nullOnDelete();
            $table->string('status', 32)->default('active')->index()->after('designation_id');
            $table->string('avatar_path')->nullable()->after('status');
            $table->timestamp('last_login_at')->nullable()->after('avatar_path');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('designation_id');
            $table->dropConstrainedForeignId('department_id');
            $table->dropColumn([
                'employee_number',
                'phone',
                'status',
                'avatar_path',
                'last_login_at',
            ]);
        });
    }
};
