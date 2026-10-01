<?php

namespace Database\Seeders;

use App\Models\CompanyType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CompanyDirectorySeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            'Client',
            'Consultant',
            'Main Contractor',
            'Contractor',
            'Subcontractor',
            'Supplier',
            'Government Entity',
            'Other',
        ] as $name) {
            CompanyType::query()->updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'description' => null,
                ],
            );
        }
    }
}
