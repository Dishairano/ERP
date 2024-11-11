<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProjectTemplate;
use App\Models\User;

class ProjectTemplateSeeder extends Seeder
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

    $templates = [
      [
        'name' => 'Software Development Project',
        'description' => 'Template for software development projects',
        'structure' => json_encode([
          'phases' => [
            ['name' => 'Planning', 'order' => 1],
            ['name' => 'Design', 'order' => 2],
            ['name' => 'Development', 'order' => 3],
            ['name' => 'Testing', 'order' => 4],
            ['name' => 'Deployment', 'order' => 5]
          ],
          'tasks' => [
            ['name' => 'Requirements Gathering', 'phase' => 'Planning', 'estimated_hours' => 40],
            ['name' => 'System Architecture', 'phase' => 'Design', 'estimated_hours' => 30],
            ['name' => 'Database Design', 'phase' => 'Design', 'estimated_hours' => 20],
            ['name' => 'Core Development', 'phase' => 'Development', 'estimated_hours' => 160],
            ['name' => 'Unit Testing', 'phase' => 'Testing', 'estimated_hours' => 40],
            ['name' => 'Integration Testing', 'phase' => 'Testing', 'estimated_hours' => 40],
            ['name' => 'Production Deployment', 'phase' => 'Deployment', 'estimated_hours' => 16]
          ],
          'risks' => [
            ['name' => 'Technical Debt', 'severity' => 'medium', 'probability' => 'high'],
            ['name' => 'Resource Availability', 'severity' => 'high', 'probability' => 'medium'],
            ['name' => 'Scope Creep', 'severity' => 'high', 'probability' => 'high']
          ],
          'team_structure' => [
            ['role' => 'Project Manager', 'count' => 1],
            ['role' => 'Tech Lead', 'count' => 1],
            ['role' => 'Developer', 'count' => 4],
            ['role' => 'QA Engineer', 'count' => 2]
          ],
          'budget_allocation' => [
            ['category' => 'Personnel', 'percentage' => 70],
            ['category' => 'Infrastructure', 'percentage' => 15],
            ['category' => 'Software Licenses', 'percentage' => 10],
            ['category' => 'Contingency', 'percentage' => 5]
          ]
        ])
      ],
      [
        'name' => 'Marketing Campaign',
        'description' => 'Template for marketing campaign projects',
        'structure' => json_encode([
          'phases' => [
            ['name' => 'Research', 'order' => 1],
            ['name' => 'Planning', 'order' => 2],
            ['name' => 'Creation', 'order' => 3],
            ['name' => 'Execution', 'order' => 4],
            ['name' => 'Analysis', 'order' => 5]
          ],
          'tasks' => [
            ['name' => 'Market Research', 'phase' => 'Research', 'estimated_hours' => 40],
            ['name' => 'Campaign Strategy', 'phase' => 'Planning', 'estimated_hours' => 30],
            ['name' => 'Content Creation', 'phase' => 'Creation', 'estimated_hours' => 60],
            ['name' => 'Campaign Launch', 'phase' => 'Execution', 'estimated_hours' => 20],
            ['name' => 'Performance Monitoring', 'phase' => 'Analysis', 'estimated_hours' => 40]
          ],
          'risks' => [
            ['name' => 'Market Response', 'severity' => 'high', 'probability' => 'medium'],
            ['name' => 'Budget Overrun', 'severity' => 'medium', 'probability' => 'medium'],
            ['name' => 'Timeline Delays', 'severity' => 'low', 'probability' => 'medium']
          ],
          'team_structure' => [
            ['role' => 'Marketing Manager', 'count' => 1],
            ['role' => 'Content Creator', 'count' => 2],
            ['role' => 'Digital Marketing Specialist', 'count' => 2],
            ['role' => 'Analyst', 'count' => 1]
          ],
          'budget_allocation' => [
            ['category' => 'Advertising', 'percentage' => 50],
            ['category' => 'Content Creation', 'percentage' => 25],
            ['category' => 'Tools & Software', 'percentage' => 15],
            ['category' => 'Contingency', 'percentage' => 10]
          ]
        ])
      ]
    ];

    foreach ($templates as $template) {
      ProjectTemplate::create(array_merge($template, [
        'is_active' => true,
        'created_by' => $adminUser->id,
        'updated_by' => $adminUser->id
      ]));
    }
  }
}
