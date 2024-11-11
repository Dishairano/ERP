<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
  public function run(): void
  {
    $currencies = [
      [
        'code' => 'EUR',
        'name' => 'Euro',
        'symbol' => '€',
        'exchange_rate' => 1.0000,
        'is_default' => true,
        'is_active' => true,
      ],
      [
        'code' => 'USD',
        'name' => 'US Dollar',
        'symbol' => '$',
        'exchange_rate' => 1.0800,
        'is_default' => false,
        'is_active' => true,
      ],
      [
        'code' => 'GBP',
        'name' => 'British Pound',
        'symbol' => '£',
        'exchange_rate' => 0.8500,
        'is_default' => false,
        'is_active' => true,
      ]
    ];

    foreach ($currencies as $currency) {
      Currency::create($currency);
    }
  }
}
