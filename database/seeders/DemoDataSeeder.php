<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = \App\Models\Role::where('slug', 'admin')->first();
        $managerRole = \App\Models\Role::where('slug', 'manager')->first();
        $memberRole = \App\Models\Role::where('slug', 'member')->first();

        // Create Admin User
        $admin = \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role_id' => $adminRole->id,
        ]);

        // Create Manager Users
        $managers = \App\Models\User::factory(2)->create(['role_id' => $managerRole->id]);

        // Create Member Users
        $members = \App\Models\User::factory(5)->create(['role_id' => $memberRole->id]);

        // Create Projects
        foreach ($managers as $manager) {
            $project = \App\Models\Project::factory()->create([
                'owner_id' => $manager->id,
                'name' => 'Project for ' . $manager->name,
            ]);

            // Add members to project
            $projectMembers = $members->random(3);
            foreach ($projectMembers as $member) {
                $project->members()->attach($member->id, ['role' => 'member']);
            }

            // Create Boards
            $board = \App\Models\Board::factory()->create(['project_id' => $project->id]);

            // Create Task Lists
            $lists = ['Todo', 'In Progress', 'Done'];
            foreach ($lists as $index => $listName) {
                $list = \App\Models\TaskList::factory()->create([
                    'board_id' => $board->id,
                    'name' => $listName,
                    'position' => $index,
                ]);

                // Create Tasks
                \App\Models\Task::factory(random_int(3, 5))->create([
                    'task_list_id' => $list->id,
                    'assignee_id' => $projectMembers->random()->id,
                ])->each(function ($task) use ($admin, $projectMembers) {
                    // Create Comments
                    \App\Models\TaskComment::factory(random_int(1, 3))->create([
                        'task_id' => $task->id,
                        'user_id' => $projectMembers->random()->id,
                    ]);

                    // Create Time Entries
                    \App\Models\TimeEntry::factory(random_int(1, 2))->create([
                        'task_id' => $task->id,
                        'user_id' => $task->assignee_id,
                    ]);
                });
            }
        }
    }
}
