<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disciplines', function (Blueprint $table) {
            $table->id();
            $table->string('code', 32)->unique();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->string('status', 32)->default('active')->index();
            $table->timestamps();
        });

        Schema::create('trades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discipline_id')->nullable()->constrained()->nullOnDelete();
            $table->string('code', 32)->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('status', 32)->default('active')->index();
            $table->timestamps();

            $table->unique(['discipline_id', 'name']);
        });

        Schema::create('project_areas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('code', 64);
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('status', 32)->default('active')->index();
            $table->timestamps();

            $table->unique(['project_id', 'code']);
            $table->index(['project_id', 'sort_order']);
        });

        Schema::create('project_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_area_id')->constrained()->cascadeOnDelete();
            $table->string('code', 64);
            $table->string('name');
            $table->string('asset_type', 128)->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('status', 32)->default('active')->index();
            $table->timestamps();

            $table->unique(['project_id', 'code']);
            $table->index(['project_id', 'project_area_id', 'sort_order']);
        });

        Schema::create('project_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_asset_id')->constrained()->cascadeOnDelete();
            $table->string('code', 64);
            $table->string('name');
            $table->decimal('elevation', 10, 2)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('status', 32)->default('active')->index();
            $table->timestamps();

            $table->unique(['project_id', 'code']);
            $table->index(['project_id', 'project_asset_id', 'sort_order']);
        });

        Schema::create('project_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_level_id')->constrained()->cascadeOnDelete();
            $table->string('code', 64);
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('status', 32)->default('active')->index();
            $table->timestamps();

            $table->unique(['project_id', 'code']);
            $table->index(['project_id', 'project_level_id', 'sort_order']);
        });

        Schema::create('work_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('code', 64);
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('discipline_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('trade_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('contractor_company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('status', 32)->default('draft')->index();
            $table->timestamps();

            $table->unique(['project_id', 'code']);
            $table->index(['project_id', 'discipline_id', 'status']);
            $table->index(['project_id', 'contractor_company_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_packages');
        Schema::dropIfExists('project_locations');
        Schema::dropIfExists('project_levels');
        Schema::dropIfExists('project_assets');
        Schema::dropIfExists('project_areas');
        Schema::dropIfExists('trades');
        Schema::dropIfExists('disciplines');
    }
};
