<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CostCategorySeeder extends Seeder
{
  public function run()
  {
    $categories = [
      [
        'name' => 'Personnel',
        'type' => 'operational',
        'description' => 'Employee salaries and benefits',
        'created_at' => now(),
        'updated_at' => now()
      ],
      [
        'name' => 'Infrastructure',
        'type' => 'capital',
        'description' => 'Hardware, software, and cloud services',
        'created_at' => now(),
        'updated_at' => now()
      ],
      [
        'name' => 'Software Licenses',
        'type' => 'operational',
        'description' => 'Software licenses and subscriptions',
        'created_at' => now(),
        'updated_at' => now()
      ],
      [
        'name' => 'Marketing',
        'type' => 'operational',
        'description' => 'Marketing and advertising expenses',
        'created_at' => now(),
        'updated_at' => now()
      ],
      [
        'name' => 'Travel',
        'type' => 'operational',
        'description' => 'Business travel and accommodation',
        'created_at' => now(),
        'updated_at' => now()
      ],
      [
        'name' => 'Training',
        'type' => 'operational',
        'description' => 'Employee training and development',
        'created_at' => now(),
        'updated_at' => now()
      ],
      [
        'name' => 'Consulting',
        'type' => 'operational',
        'description' => 'External consultants and contractors',
        'created_at' => now(),
        'updated_at' => now()
      ],
      [
        'name' => 'Office Supplies',
        'type' => 'operational',
        'description' => 'Office supplies and equipment',
        'created_at' => now(),
        'updated_at' => now()
      ],
      [
        'name' => 'Miscellaneous',
        'type' => 'operational',
        'description' => 'Other project-related expenses',
        'created_at' => now(),
        'updated_at' => now()
      ]
    ];

    foreach ($categories as $category) {
      DB::table('cost_categories')->insert($category);
    }
  }
}
