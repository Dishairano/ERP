<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class HrmSeeder extends Seeder
{
  public function run()
  {
    // Create admin user
    $adminId = DB::table('users')->insertGetId([
      'name' => 'Admin User',
      'email' => 'admin@example.com',
      'password' => Hash::make('password'),
      'created_at' => now(),
      'updated_at' => now(),
    ]);

    // Create interviewer user
    $interviewerId = DB::table('users')->insertGetId([
      'name' => 'Interviewer User',
      'email' => 'interviewer@example.com',
      'password' => Hash::make('password'),
      'created_at' => now(),
      'updated_at' => now(),
    ]);

    // Create job posting
    $jobPostingId = DB::table('hrm_job_postings')->insertGetId([
      'created_by' => $adminId,
      'title' => 'Senior Software Engineer',
      'description' => 'We are looking for an experienced software engineer...',
      'department' => 'Engineering',
      'location' => 'Amsterdam',
      'employment_type' => 'full-time',
      'experience_level' => '5+ years',
      'salary_min' => 70000,
      'salary_max' => 90000,
      'required_skills' => json_encode(['PHP', 'Laravel', 'Vue.js', 'MySQL']),
      'responsibilities' => json_encode(['Lead development team', 'Design system architecture']),
      'qualifications' => json_encode(['Bachelor\'s degree in Computer Science', '5+ years experience']),
      'benefits' => json_encode(['Health insurance', '25 vacation days', 'Remote work']),
      'posting_date' => now(),
      'closing_date' => now()->addMonths(1),
      'status' => 'active',
      'created_at' => now(),
      'updated_at' => now(),
    ]);

    // Create multiple candidates
    $candidateIds = [];
    $names = [
      ['John', 'Doe'],
      ['Jane', 'Smith'],
      ['Michael', 'Johnson']
    ];

    foreach ($names as $index => $name) {
      $candidateIds[] = DB::table('hrm_candidates')->insertGetId([
        'job_posting_id' => $jobPostingId,
        'created_by' => $adminId,
        'first_name' => $name[0],
        'last_name' => $name[1],
        'email' => strtolower($name[0] . '.' . $name[1] . '@example.com'),
        'phone' => '+31612345' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
        'city' => 'Amsterdam',
        'country' => 'Netherlands',
        'current_company' => 'Tech Corp',
        'current_position' => 'Software Engineer',
        'experience_years' => 5 + $index,
        'education_level' => 'bachelor',
        'field_of_study' => 'Computer Science',
        'skills' => json_encode(['PHP', 'Laravel', 'Vue.js', 'MySQL']),
        'status' => ['applied', 'screening', 'interviewing'][$index],
        'created_at' => now()->subDays($index * 2),
        'updated_at' => now()->subDays($index * 2),
      ]);
    }

    // Create interviews for each candidate
    foreach ($candidateIds as $index => $candidateId) {
      DB::table('hrm_interviews')->insert([
        'candidate_id' => $candidateId,
        'job_posting_id' => $jobPostingId,
        'interviewer_id' => $interviewerId,
        'created_by' => $adminId,
        'interview_type' => ['technical', 'behavioral', 'cultural'][$index],
        'round_number' => 1,
        'scheduled_date' => now()->addDays($index + 1),
        'scheduled_time' => '14:00:00',
        'duration_minutes' => 60,
        'status' => 'scheduled',
        'created_at' => now(),
        'updated_at' => now(),
      ]);
    }

    // Create assessments for each candidate
    foreach ($candidateIds as $index => $candidateId) {
      DB::table('hrm_assessments')->insert([
        'candidate_id' => $candidateId,
        'job_posting_id' => $jobPostingId,
        'assessor_id' => $interviewerId,
        'created_by' => $adminId,
        'title' => ['Technical Skills', 'Coding Challenge', 'System Design'][$index] . ' Assessment',
        'assessment_type' => ['technical', 'coding', 'design'][$index],
        'scheduled_date' => now()->addDays($index + 2),
        'scheduled_time' => '10:00:00',
        'duration_minutes' => 120,
        'max_score' => 100,
        'passing_score' => 70,
        'status' => 'scheduled',
        'created_at' => now(),
        'updated_at' => now(),
      ]);
    }
  }
}
