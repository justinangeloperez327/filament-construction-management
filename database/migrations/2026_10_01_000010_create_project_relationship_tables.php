<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('role_id')->constrained()->restrictOnDelete();
            $table->date('started_at')->nullable();
            $table->date('ended_at')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['project_id', 'user_id', 'role_id']);
            $table->index(['project_id', 'user_id', 'is_active']);
        });

        Schema::create('project_companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_id')->constrained()->restrictOnDelete();
            $table->string('role', 64)->index();
            $table->boolean('is_primary')->default(false)->index();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['project_id', 'company_id', 'role']);
            $table->index(['project_id', 'role', 'is_primary']);
        });

        Schema::create('project_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_contact_id')->constrained()->restrictOnDelete();
            $table->string('project_role')->nullable();
            $table->boolean('is_primary')->default(false)->index();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['project_id', 'company_contact_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_contacts');
        Schema::dropIfExists('project_companies');
        Schema::dropIfExists('project_members');
    }
};
