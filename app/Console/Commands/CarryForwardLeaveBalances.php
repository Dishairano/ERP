<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\LeaveType;
use App\Models\LeaveBalance;
use Illuminate\Console\Command;

class CarryForwardLeaveBalances extends Command
{
    protected $signature = 'leave:carry-forward {from_year? : The year to carry forward from}';
    protected $description = 'Carry forward leave balances to next year';

    public function handle()
    {
        $fromYear = $this->argument('from_year') ?? date('Y');
        $toYear = $fromYear + 1;
        $users = User::all();

        $this->info("Carrying forward leave balances from {$fromYear} to {$toYear}");
        $bar = $this->output->createProgressBar(count($users));
        $bar->start();

        $leaveTypes = LeaveType::where('allow_carry_forward', true)->get();
        $carriedForward = [];

        foreach ($users as $user) {
            foreach ($leaveTypes as $leaveType) {
                $balance = LeaveBalance::where([
                    'user_id' => $user->id,
                    'leave_type_id' => $leaveType->id,
                    'year' => $fromYear
                ])->first();

                if ($balance && $balance->remaining_days > 0) {
                    $newBalance = $balance->carryForward();
                    if ($newBalance) {
                        $carriedForward[] = [
                            'user' => $user->name,
                            'type' => $leaveType->name,
                            'days' => $newBalance->total_days - $leaveType->days_per_year
                        ];
                    }
                }
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        if (count($carriedForward) > 0) {
            $this->info('Leave balances carried forward:');
            $this->table(
                ['User', 'Leave Type', 'Days Carried Forward'],
                $carriedForward
            );
        } else {
            $this->info('No leave balances to carry forward.');
        }

        // Initialize new balances for leave types that don't carry forward
        $this->info("Initializing new balances for {$toYear}");
        $nonCarryForwardTypes = LeaveType::where('allow_carry_forward', false)->get();

        foreach ($users as $user) {
            foreach ($nonCarryForwardTypes as $leaveType) {
                LeaveBalance::getBalance($user->id, $leaveType->id, $toYear);
            }
        }

        $this->info('Leave balances carried forward and initialized successfully!');
        return Command::SUCCESS;
    }
}
