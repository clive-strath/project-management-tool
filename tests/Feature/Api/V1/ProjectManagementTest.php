<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use App\Models\Role;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $member;

    protected function setUp(): void
    {
        parent::setUp();
        
        $adminRole = Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $memberRole = Role::create(['name' => 'Member', 'slug' => 'member']);

        $this->admin = User::factory()->create(['role_id' => $adminRole->id]);
        $this->member = User::factory()->create(['role_id' => $memberRole->id]);
    }

    public function test_user_can_list_their_projects()
    {
        $project = Project::factory()->create(['owner_id' => $this->member->id]);
        
        $response = $this->actingAs($this->member, 'sanctum')->getJson('/api/v1/projects');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    public function test_admin_can_list_all_projects()
    {
        Project::factory()->create(['owner_id' => $this->member->id]);
        
        $response = $this->actingAs($this->admin, 'sanctum')->getJson('/api/v1/projects');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    public function test_member_cannot_create_project_without_permission()
    {
        // Our policy says only Admin/Manager can create? Let's check ProjectPolicy.
        // Actually, ProjectPolicy: $user->role?->slug === 'admin' || $user->role?->slug === 'manager'
        
        $response = $this->actingAs($this->member, 'sanctum')->postJson('/api/v1/projects', [
            'name' => 'New Project',
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_create_project()
    {
        $response = $this->actingAs($this->admin, 'sanctum')->postJson('/api/v1/projects', [
            'name' => 'Admin Project',
            'description' => 'Test Description',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('projects', ['name' => 'Admin Project']);
    }
}
