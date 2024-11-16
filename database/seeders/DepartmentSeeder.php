<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CoreHrmDepartmentModal;

class DepartmentSeeder extends Seeder
{
  public function run()
  {
    $departments = [
      [
        'name' => 'Finance',
        'code' => 'FIN',
        'description' => 'Financial department',
        'level' => 0,
        'is_active' => true
      ],
      [
        'name' => 'Human Resources',
        'code' => 'HR',
        'description' => 'Human Resources department',
        'level' => 0,
        'is_active' => true
      ],
      [
        'name' => 'Information Technology',
        'code' => 'IT',
        'description' => 'IT department',
        'level' => 0,
        'is_active' => true
      ],
      [
        'name' => 'Operations',
        'code' => 'OPS',
        'description' => 'Operations department',
        'level' => 0,
        'is_active' => true
      ],
      [
        'name' => 'Marketing',
        'code' => 'MKT',
        'description' => 'Marketing department',
        'level' => 0,
        'is_active' => true
      ]
    ];

    foreach ($departments as $department) {
      CoreHrmDepartmentModal::create($department);
    }
  }
}
