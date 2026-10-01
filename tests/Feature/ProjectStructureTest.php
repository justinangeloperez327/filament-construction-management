<?php

namespace Tests\Feature;

use App\Models\Discipline;
use App\Models\Project;
use App\Models\ProjectArea;
use App\Models\ProjectAsset;
use App\Models\ProjectLevel;
use App\Models\ProjectLocation;
use App\Models\ProjectMember;
use App\Models\Role;
use App\Models\Trade;
use App\Models\User;
use App\Models\WorkPackage;
use Database\Seeders\AccessControlSeeder;
use Database\Seeders\ProjectStructureSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ProjectStructureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            AccessControlSeeder::class,
            ProjectStructureSeeder::class,
        ]);
    }

    public function test_project_structure_builds_area_asset_level_location_hierarchy(): void
    {
        $project = Project::factory()->create();
        $area = ProjectArea::factory()->for($project)->create();
        $asset = ProjectAsset::factory()->forArea($area)->create();
        $level = ProjectLevel::factory()->forAsset($asset)->create();
        $location = ProjectLocation::factory()->forLevel($level)->create();

        $this->assertTrue($location->level->is($level));
        $this->assertTrue($level->asset->is($asset));
        $this->assertTrue($asset->area->is($area));
        $this->assertTrue($area->project->is($project));
    }

    public function test_asset_cannot_reference_area_from_another_project(): void
    {
        $project = Project::factory()->create();
        $otherArea = ProjectArea::factory()->create();

        $this->expectException(ValidationException::class);

        ProjectAsset::factory()->create([
            'project_id' => $project->getKey(),
            'project_area_id' => $otherArea->getKey(),
        ]);
    }

    public function test_work_package_trade_must_belong_to_selected_discipline(): void
    {
        $discipline = Discipline::factory()->create();
        $otherDiscipline = Discipline::factory()->create();
        $trade = Trade::factory()->for($otherDiscipline)->create();

        $this->expectException(ValidationException::class);

        WorkPackage::factory()->create([
            'discipline_id' => $discipline->getKey(),
            'trade_id' => $trade->getKey(),
        ]);
    }

    public function test_project_manager_can_manage_project_structure_only_on_assigned_project(): void
    {
        $assigned = Project::factory()->create();
        $other = Project::factory()->create();
        $manager = User::factory()->create();
        $role = Role::query()->where('slug', 'project-manager')->firstOrFail();

        ProjectMember::factory()->for($assigned)->for($manager)->create([
            'role_id' => $role->getKey(),
        ]);

        $this->assertTrue($assigned->userHasPermission($manager, 'project_structure.create'));
        $this->assertTrue($assigned->userHasPermission($manager, 'work_packages.update'));
        $this->assertFalse($other->userHasPermission($manager, 'project_structure.view'));
    }

    public function test_reference_data_is_seeded(): void
    {
        $this->assertDatabaseHas('disciplines', ['code' => 'CIV', 'name' => 'Civil']);
        $this->assertDatabaseHas('disciplines', ['code' => 'ELE', 'name' => 'Electrical']);
        $this->assertDatabaseHas('trades', ['code' => 'HVAC', 'name' => 'HVAC']);
    }
}
