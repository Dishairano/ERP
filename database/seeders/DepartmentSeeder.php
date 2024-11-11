<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
  public function run()
  {
    $departments = [
      [
        'name' => 'Finance',
        'code' => 'FIN',
        'description' => 'Financial department'
      ],
      [
        'name' => 'Human Resources',
        'code' => 'HR',
        'description' => 'Human Resources department'
      ],
      [
        'name' => 'Information Technology',
        'code' => 'IT',
        'description' => 'IT department'
      ],
      [
        'name' => 'Operations',
        'code' => 'OPS',
        'description' => 'Operations department'
      ],
      [
        'name' => 'Marketing',
        'code' => 'MKT',
        'description' => 'Marketing department'
      ]
    ];

    foreach ($departments as $department) {
      Department::create($department);
    }
  }
}
