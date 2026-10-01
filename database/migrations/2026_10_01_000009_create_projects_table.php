<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_number', 64)->unique();
            $table->string('name');
            $table->string('short_name', 128)->nullable();
            $table->text('description')->nullable();
            $table->string('project_type', 128)->nullable()->index();
            $table->string('location')->nullable();
            $table->string('country', 128)->nullable()->index();
            $table->string('city', 128)->nullable()->index();
            $table->decimal('contract_value', 18, 2)->nullable();
            $table->char('currency', 3)->default('AED');
            $table->date('start_date')->nullable();
            $table->date('planned_completion_date')->nullable()->index();
            $table->date('actual_completion_date')->nullable();
            $table->date('defects_liability_end_date')->nullable();
            $table->decimal('planned_progress', 5, 2)->default(0);
            $table->decimal('actual_progress', 5, 2)->default(0);
            $table->string('status', 64)->default('planning')->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'planned_completion_date']);
            $table->index(['country', 'city']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
