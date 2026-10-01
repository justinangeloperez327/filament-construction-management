<?php

namespace Database\Factories;

use App\Enums\CompanyDocumentType;
use App\Models\Company;
use App\Models\CompanyDocument;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyDocumentFactory extends Factory
{
    protected $model = CompanyDocument::class;

    public function definition(): array
    {
        $issuedAt = fake()->dateTimeBetween('-2 years', '-1 month');

        return [
            'company_id' => Company::factory(),
            'type' => CompanyDocumentType::TradeLicense,
            'title' => 'Trade License',
            'document_number' => fake()->numerify('LIC-########'),
            'issued_at' => $issuedAt,
            'expires_at' => fake()->dateTimeBetween('+1 month', '+2 years'),
            'file_path' => 'documents/companies/example.pdf',
            'notes' => null,
        ];
    }
}
