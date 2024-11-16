<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CoreProjectModal;
use App\Models\CoreProjectTaskModal;
use App\Models\User;
use Carbon\Carbon;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        $adminUser = User::where('email', 'admin@example.com')->first();
        if (!$adminUser) {
            throw new \Exception('Admin user not found. Please ensure AdminUserSeeder has been run.');
        }

        $projects = [
            [
                'name' => 'ERP Implementation',
                'description' => 'Implementation of enterprise resource planning system',
                'status' => 'in_progress',
                'priority' => 'high',
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addYear(),
                'budget' => 500000.00,
                'progress' => 25,
                'manager_id' => $adminUser->id,
                'tasks' => [
                    [
                        'title' => 'Requirements Analysis',
                        'description' => 'Gather and analyze system requirements',
                        'status' => 'in_progress',
                        'priority' => 'high',
                        'assignee_id' => $adminUser->id,
                        'start_date' => Carbon::now(),
                        'due_date' => Carbon::now()->addWeeks(2),
                        'estimated_hours' => 80
                    ],
                    [
                        'title' => 'System Design',
                        'description' => 'Design system architecture and modules',
                        'status' => 'pending',
                        'priority' => 'high',
                        'assignee_id' => $adminUser->id,
                        'start_date' => Carbon::now()->addWeeks(2),
                        'due_date' => Carbon::now()->addWeeks(6),
                        'estimated_hours' => 160
                    ]
                ]
            ],
            [
                'name' => 'Website Redesign',
                'description' => 'Company website redesign project',
                'status' => 'in_progress',
                'priority' => 'medium',
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(6),
                'budget' => 100000.00,
                'progress' => 15,
                'manager_id' => $adminUser->id,
                'tasks' => [
                    [
                        'title' => 'UI/UX Design',
                        'description' => 'Create website design mockups',
                        'status' => 'pending',
                        'priority' => 'high',
                        'assignee_id' => $adminUser->id,
                        'start_date' => Carbon::now(),
                        'due_date' => Carbon::now()->addWeeks(3),
                        'estimated_hours' => 60
                    ],
                    [
                        'title' => 'Frontend Development',
                        'description' => 'Implement website frontend',
                        'status' => 'pending',
                        'priority' => 'high',
                        'assignee_id' => $adminUser->id,
                        'start_date' => Carbon::now()->addWeeks(3),
                        'due_date' => Carbon::now()->addWeeks(8),
                        'estimated_hours' => 120
                    ]
                ]
            ]
        ];

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
