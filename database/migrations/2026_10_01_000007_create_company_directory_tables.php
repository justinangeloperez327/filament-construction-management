<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('legal_name');
            $table->string('trading_name')->nullable();
            $table->string('code', 64)->unique();
            $table->string('registration_number', 128)->nullable()->index();
            $table->string('vat_number', 128)->nullable()->index();
            $table->string('phone', 64)->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('country', 128)->nullable()->index();
            $table->string('city', 128)->nullable()->index();
            $table->string('status', 32)->default('active')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['legal_name', 'status']);
        });

        Schema::create('company_company_type', function (Blueprint $table) {
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_type_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->primary(['company_id', 'company_type_id']);
        });

        Schema::create('company_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('job_title')->nullable();
            $table->string('department')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile', 64)->nullable();
            $table->string('office_phone', 64)->nullable();
            $table->boolean('is_primary')->default(false)->index();
            $table->string('status', 32)->default('active')->index();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'name']);
        });

        Schema::create('company_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('type', 32)->default('office')->index();
            $table->string('address_line_1');
            $table->string('address_line_2')->nullable();
            $table->string('city', 128)->nullable()->index();
            $table->string('state', 128)->nullable();
            $table->string('postal_code', 64)->nullable();
            $table->string('country', 128)->nullable()->index();
            $table->boolean('is_primary')->default(false)->index();
            $table->timestamps();
        });

        Schema::create('company_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('type', 64)->index();
            $table->string('title');
            $table->string('document_number', 128)->nullable()->index();
            $table->date('issued_at')->nullable();
            $table->date('expires_at')->nullable()->index();
            $table->string('file_path');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'type', 'expires_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_documents');
        Schema::dropIfExists('company_addresses');
        Schema::dropIfExists('company_contacts');
        Schema::dropIfExists('company_company_type');
        Schema::dropIfExists('companies');
        Schema::dropIfExists('company_types');
    }
};
