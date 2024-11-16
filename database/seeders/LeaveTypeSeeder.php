<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leaveTypes = [
            [
                'name' => 'Annual Leave',
                'code' => 'AL',
                'description' => 'Regular annual leave entitlement',
                'days_per_year' => 20,
                'requires_approval' => true,
                'paid' => true,
                'allow_carry_forward' => true,
                'max_carry_forward_days' => 5
            ],
            [
                'name' => 'Sick Leave',
                'code' => 'SL',
                'description' => 'Leave for medical reasons',
                'days_per_year' => 10,
                'requires_approval' => true,
                'paid' => true,
                'allow_carry_forward' => false
            ],
            [
                'name' => 'Unpaid Leave',
                'code' => 'UL',
                'description' => 'Leave without pay',
                'days_per_year' => 0,
                'requires_approval' => true,
                'paid' => false,
                'allow_carry_forward' => false
            ],
            [
                'name' => 'Maternity Leave',
                'code' => 'ML',
                'description' => 'Leave for childbirth and care',
                'days_per_year' => 90,
                'requires_approval' => true,
                'paid' => true,
                'allow_carry_forward' => false
            ],
            [
                'name' => 'Paternity Leave',
                'code' => 'PL',
                'description' => 'Leave for new fathers',
                'days_per_year' => 10,
                'requires_approval' => true,
                'paid' => true,
                'allow_carry_forward' => false
            ],
            [
                'name' => 'Bereavement Leave',
                'code' => 'BL',
                'description' => 'Leave for family bereavement',
                'days_per_year' => 5,
                'requires_approval' => true,
                'paid' => true,
                'allow_carry_forward' => false
            ],
            [
                'name' => 'Study Leave',
                'code' => 'STL',
                'description' => 'Leave for educational purposes',
                'days_per_year' => 5,
                'requires_approval' => true,
                'paid' => true,
                'allow_carry_forward' => false
            ],
            [
                'name' => 'Marriage Leave',
                'code' => 'MRL',
                'description' => 'Leave for getting married',
                'days_per_year' => 3,
                'requires_approval' => true,
                'paid' => true,
                'allow_carry_forward' => false
            ]
        ];

        foreach ($leaveTypes as $leaveType) {
            LeaveType::updateOrCreate(
                ['code' => $leaveType['code']],
                $leaveType
            );
        }
    }
}
