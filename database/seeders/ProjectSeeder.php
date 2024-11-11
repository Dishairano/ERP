<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\ProjectTemplate;
use App\Models\User;
use App\Models\ProjectPhase;
use App\Models\ProjectTask;
use App\Models\ProjectRisk;

class ProjectSeeder extends Seeder
{
  public function run()
  {
    $adminUser = User::where('role', 'admin')->first();
    if (!$adminUser) {
      $adminUser = User::create([
        'name' => 'System Admin',
        'email' => 'admin@system.com',
        'password' => bcrypt('admin123'),
        'role' => 'admin',
        'is_active' => true
      ]);
    }

    $projects = [
      [
        'name' => 'ERP Implementation',
        'code' => 'ERP-2024',
        'description' => 'Implementation of enterprise resource planning system',
        'status' => 'active',
        'priority' => 'high',
        'start_date' => '2024-01-01',
        'end_date' => '2024-12-31',
        'budget' => 500000.00,
        'manager_id' => $adminUser->id,
        'template_name' => 'Software Development Project'
      ],
      [
        'name' => 'Website Redesign',
        'code' => 'WEB-2024',
        'description' => 'Company website redesign project',
        'status' => 'planned',
        'priority' => 'medium',
        'start_date' => '2024-02-01',
        'end_date' => '2024-06-30',
        'budget' => 100000.00,
        'manager_id' => $adminUser->id,
        'template_name' => 'Software Development Project'
      ],
      [
        'name' => 'Digital Marketing Campaign',
        'code' => 'MKT-2024',
        'description' => 'Q1 2024 digital marketing campaign',
        'status' => 'planned',
        'priority' => 'medium',
        'start_date' => '2024-01-15',
        'end_date' => '2024-03-31',
        'budget' => 75000.00,
        'manager_id' => $adminUser->id,
        'template_name' => 'Marketing Campaign'
      ],
      [
        'name' => 'Office Renovation',
        'code' => 'REN-2024',
        'description' => 'Main office renovation project',
        'status' => 'planned',
        'priority' => 'low',
        'start_date' => '2024-03-01',
        'end_date' => '2024-05-31',
        'budget' => 250000.00,
        'manager_id' => $adminUser->id,
        'template_name' => null
      ],
      [
        'name' => 'Employee Training Program',
        'code' => 'TRN-2024',
        'description' => '2024 employee training and development program',
        'status' => 'planned',
        'priority' => 'medium',
        'start_date' => '2024-01-01',
        'end_date' => '2024-12-31',
        'budget' => 150000.00,
        'manager_id' => $adminUser->id,
        'template_name' => null
      ]
    ];

    foreach ($projects as $projectData) {
      $templateName = $projectData['template_name'];
      unset($projectData['template_name']);

      // Create the project
      $project = Project::create($projectData);

      // Apply template if specified
      if ($templateName) {
        $template = ProjectTemplate::where('name', $templateName)->first();
        if ($template) {
          $structure = json_decode($template->structure, true);

          // Create phases
          if (isset($structure['phases'])) {
            foreach ($structure['phases'] as $phaseData) {
              ProjectPhase::create([
                'project_id' => $project->id,
                'name' => $phaseData['name'],
                'order' => $phaseData['order'],
                'start_date' => $project->start_date,
                'end_date' => $project->end_date,
                'status' => 'planned'
              ]);
            }
          }

          // Create tasks
          if (isset($structure['tasks'])) {
            foreach ($structure['tasks'] as $taskData) {
              $phase = ProjectPhase::where('project_id', $project->id)
                ->where('name', $taskData['phase'])
                ->first();

              if ($phase) {
                ProjectTask::create([
                  'project_id' => $project->id,
                  'phase_id' => $phase->id,
                  'name' => $taskData['name'],
                  'estimated_hours' => $taskData['estimated_hours'],
                  'start_date' => $phase->start_date,
                  'due_date' => $phase->end_date,
                  'priority' => 'medium',
                  'status' => 'todo'
                ]);
              }
            }
          }

          // Create risks
          if (isset($structure['risks'])) {
            foreach ($structure['risks'] as $riskData) {
              ProjectRisk::create([
                'project_id' => $project->id,
                'name' => $riskData['name'],
                'description' => $riskData['name'],
                'probability' => $riskData['probability'],
                'priority' => $riskData['severity'], // Use severity from template as priority
                'status' => 'identified',
                'owner_id' => $adminUser->id
              ]);
            }
          }
        }
      }
    }
  }
}
