<?php

namespace Database\Factories;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PermissionFactory extends Factory
{
    protected $model = Permission::class;

    public function definition(): array
    {
        $resource = Str::snake(fake()->unique()->word());
        $action = fake()->randomElement(['view', 'create', 'update', 'delete']);

        return [
            'name' => Str::headline($action.' '.$resource),
            'slug' => "{$resource}.{$action}",
            'group' => 'Custom',
            'description' => null,
            'is_system' => false,
        ];
    }
}
