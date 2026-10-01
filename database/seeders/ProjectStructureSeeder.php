<?php

namespace Database\Seeders;

use App\Models\Discipline;
use App\Models\Trade;
use Illuminate\Database\Seeder;

class ProjectStructureSeeder extends Seeder
{
    public function run(): void
    {
        $disciplines = [
            'CIV' => 'Civil',
            'STR' => 'Structural',
            'ARC' => 'Architectural',
            'MEC' => 'Mechanical',
            'ELE' => 'Electrical',
            'PLB' => 'Plumbing',
            'FIR' => 'Fire Fighting',
            'ICT' => 'ICT',
            'ELV' => 'ELV',
            'LND' => 'Landscape',
            'INF' => 'Infrastructure',
            'OTH' => 'Other',
        ];

        foreach ($disciplines as $code => $name) {
            Discipline::query()->updateOrCreate(
                ['code' => $code],
                [
                    'name' => $name,
                    'status' => 'active',
                ],
            );
        }

        $trades = [
            ['Masonry', 'MAS', 'Civil'],
            ['Concrete', 'CON', 'Structural'],
            ['Steel', 'STL', 'Structural'],
            ['Finishes', 'FIN', 'Architectural'],
            ['HVAC', 'HVAC', 'Mechanical'],
            ['Electrical Power', 'PWR', 'Electrical'],
            ['Lighting', 'LGT', 'Electrical'],
            ['Drainage', 'DRN', 'Plumbing'],
            ['Water Supply', 'WTR', 'Plumbing'],
            ['Fire Alarm', 'FAL', 'Fire Fighting'],
            ['Fire Protection', 'FPR', 'Fire Fighting'],
            ['Structured Cabling', 'SCB', 'ICT'],
            ['Security Systems', 'SEC', 'ELV'],
            ['Softscape', 'SFT', 'Landscape'],
            ['Hardscape', 'HRD', 'Landscape'],
        ];

        foreach ($trades as [$name, $code, $discipline]) {
            Trade::query()->updateOrCreate(
                ['code' => $code],
                [
                    'discipline_id' => Discipline::query()->where('name', $discipline)->value('id'),
                    'name' => $name,
                    'status' => 'active',
                ],
            );
        }
    }
}
