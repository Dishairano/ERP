<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class InitializeLeaveBalances extends Command
{
    protected $signature = 'leave:init-balances {year? : The year to initialize balances for}';
    protected $description = 'Initialize leave balances for all users';

    public function handle()
    {
        $year = $this->argument('year') ?? date('Y');
        $users = User::all();
        $bar = $this->output->createProgressBar(count($users));

        $this->info("Initializing leave balances for year {$year}");
        $bar->start();

        foreach ($users as $user) {
            $balances = $user->initializeLeaveBalances($year);

            // Log the balances
            foreach ($balances as $balance) {
                $this->line(sprintf(
                    "User: %s, Type: %s, Total Days: %s",
                    $user->name,
                    $balance->leaveType->name,
                    $balance->total_days
                ));
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Leave balances initialized successfully!');

        return Command::SUCCESS;
    }
}
