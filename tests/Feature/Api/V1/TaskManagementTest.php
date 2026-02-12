<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use App\Models\Role;
use App\Models\Project;
use App\Models\Board;
use App\Models\TaskList;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $project;
    protected $taskList;

    protected function setUp(): void
    {
        parent::setUp();
        
        $role = Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $this->user = User::factory()->create(['role_id' => $role->id]);
        $this->project = Project::factory()->create(['owner_id' => $this->user->id]);
        $board = Board::factory()->create(['project_id' => $this->project->id]);
        $this->taskList = TaskList::factory()->create(['board_id' => $board->id]);
    }

    public function test_user_can_create_task()
    {
        $response = $this->actingAs($this->user, 'sanctum')->postJson("/api/v1/task-lists/{$this->taskList->id}/tasks", [
            'title' => 'New Task',
            'description' => 'Task description',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('tasks', ['title' => 'New Task']);
    }

    public function test_user_can_update_task()
    {
        $task = Task::factory()->create(['task_list_id' => $this->taskList->id]);

        $response = $this->actingAs($this->user, 'sanctum')->putJson("/api/v1/tasks/{$task->id}", [
            'title' => 'Updated Task',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'title' => 'Updated Task']);
    }

    public function test_user_can_delete_task()
    {
        $task = Task::factory()->create(['task_list_id' => $this->taskList->id]);

        $response = $this->actingAs($this->user, 'sanctum')->deleteJson("/api/v1/tasks/{$task->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
