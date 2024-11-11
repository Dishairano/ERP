<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    User::create([
      'name' => 'Your Name',
      'email' => 'your_email@example.com',
      'password' => Hash::make('your_password'), // Hash the password
    ]);
  }
}
