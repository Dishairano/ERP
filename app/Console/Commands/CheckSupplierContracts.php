<?php

namespace App\Console\Commands;

use App\Models\Supplier;
use App\Models\SupplierNotification;
use Illuminate\Console\Command;

class CheckSupplierContracts extends Command
{
  protected $signature = 'suppliers:check-contracts';
  protected $description = 'Check for expiring supplier contracts and send notifications';

  public function handle()
  {
    $this->info('Checking supplier contracts...');

    $suppliers = Supplier::with(['contracts' => function ($query) {
      $query->where('status', 'active');
    }])->get();

    $notificationCount = 0;

    foreach ($suppliers as $supplier) {
      foreach ($supplier->contracts as $contract) {
        if ($contract->isExpiring()) {
          SupplierNotification::createContractExpiringNotification($contract);
          $notificationCount++;
        }
      }
    }

    $this->info("Created {$notificationCount} contract expiry notifications.");
  }
}
