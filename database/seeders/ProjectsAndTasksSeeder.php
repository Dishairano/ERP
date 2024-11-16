<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CoreProjectModal;
use App\Models\CoreProjectTaskModal;
use App\Models\User;
use Carbon\Carbon;

class ProjectsAndTasksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a default user if none exists
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        // Create sample projects
        $projects = [
            [
                'name' => 'Website Development',
                'description' => 'Company website development project',
                'status' => 'active',
                'priority' => 'high',
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(3),
                'budget' => 50000,
                'progress' => 0,
                'manager_id' => $user->id,
                'tasks' => [
                    [
                        'title' => 'Design Homepage',
                        'description' => 'Create homepage design mockup',
                        'status' => 'in_progress',
                        'priority' => 'high',
                        'assigned_to' => $user->id,
                        'start_date' => Carbon::now(),
                        'due_date' => Carbon::now()->addWeeks(1),
                        'estimated_hours' => 40
                    ],
                    [
                        'title' => 'Develop Backend API',
                        'description' => 'Implement REST API endpoints',
                        'status' => 'pending',
                        'priority' => 'high',
                        'assigned_to' => $user->id,
                        'start_date' => Carbon::now()->addWeeks(1),
                        'due_date' => Carbon::now()->addWeeks(3),
                        'estimated_hours' => 80
                    ]
                ]
            ],
            [
                'name' => 'Mobile App Development',
                'description' => 'Company mobile app development',
                'status' => 'active',
                'priority' => 'medium',
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(4),
                'budget' => 75000,
                'progress' => 0,
                'manager_id' => $user->id,
                'tasks' => [
                    [
                        'title' => 'UI/UX Design',
                        'description' => 'Design user interface mockups',
                        'status' => 'pending',
                        'priority' => 'high',
                        'assigned_to' => $user->id,
                        'start_date' => Carbon::now(),
                        'due_date' => Carbon::now()->addWeeks(2),
                        'estimated_hours' => 60
                    ],
                    [
                        'title' => 'Core Features Development',
                        'description' => 'Implement core app features',
                        'status' => 'pending',
                        'priority' => 'high',
                        'assigned_to' => $user->id,
                        'start_date' => Carbon::now()->addWeeks(2),
                        'due_date' => Carbon::now()->addWeeks(6),
                        'estimated_hours' => 120
                    ]
                ]
            ]
        ];

        // Create projects and their tasks
        foreach ($projects as $projectData) {
            $tasks = $projectData['tasks'];
            unset($projectData['tasks']);

            $project = CoreProjectModal::create($projectData);

            foreach ($tasks as $taskData) {
                $taskData['project_id'] = $project->id;
                CoreProjectTaskModal::create($taskData);
            }
        }
    }
}
