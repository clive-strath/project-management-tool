<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Project;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::whereIn('email', ['john@example.com', 'jane@example.com', 'admin@example.com'])->get();
        
        if ($users->count() < 3) {
            $this->command->warn('Test users not found. Please run TestUserSeeder first.');
            return;
        }

        $john = $users->where('email', 'john@example.com')->first();
        $jane = $users->where('email', 'jane@example.com')->first();
        $admin = $users->where('email', 'admin@example.com')->first();

        // Create test projects
        $projects = [
            [
                'name' => 'Website Redesign',
                'description' => 'Complete redesign of company website with modern UI/UX',
                'owner_id' => $john->id,
                'status' => 'active'
            ],
            [
                'name' => 'Mobile App Development',
                'description' => 'Native mobile app for iOS and Android platforms',
                'owner_id' => $jane->id,
                'status' => 'active'
            ],
            [
                'name' => 'Marketing Campaign',
                'description' => 'Q1 2026 digital marketing campaign and analytics',
                'owner_id' => $admin->id,
                'status' => 'active'
            ]
        ];

        foreach ($projects as $projectData) {
            $project = Project::create($projectData);
            
            // Add the owner as a project lead
            $project->addMember($project->owner_id, 'lead');
            
            // Add some additional members to projects
            if ($project->name === 'Website Redesign') {
                $project->addMember($jane->id, 'member');
            } elseif ($project->name === 'Mobile App Development') {
                $project->addMember($john->id, 'member');
            } elseif ($project->name === 'Marketing Campaign') {
                $project->addMember($john->id, 'lead');
                $project->addMember($jane->id, 'member');
            }
        }

        $this->command->info('Test projects created successfully!');
    }
}
