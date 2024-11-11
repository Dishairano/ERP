<?php

namespace App\Exports;

use App\Models\DemandForecast;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DemandForecastExport implements FromCollection, WithHeadings, WithMapping
{
  public function collection()
  {
    return DemandForecast::with(['product', 'region', 'accuracy'])
      ->orderBy('forecast_date', 'desc')
      ->get();
  }

  public function headings(): array
  {
    return [
      'Product',
      'Region',
      'Forecast Date',
      'Forecast Quantity',
      'Forecast Value',
      'Method',
      'Confidence Level',
      'Actual Quantity',
      'Actual Value',
      'Accuracy',
      'Bias',
      'Created At'
    ];
  }

  public function map($forecast): array
  {
    return [
      $forecast->product->name,
      $forecast->region ? $forecast->region->name : 'All Regions',
      $forecast->forecast_date->format('Y-m-d'),
      $forecast->forecast_quantity,
      $forecast->forecast_value,
      ucfirst(str_replace('_', ' ', $forecast->forecast_method)),
      number_format($forecast->confidence_level, 1) . '%',
      $forecast->accuracy ? $forecast->accuracy->actual_quantity : 'N/A',
      $forecast->accuracy ? $forecast->accuracy->actual_value : 'N/A',
      $forecast->accuracy ? number_format($forecast->accuracy->accuracy_percentage, 1) . '%' : 'N/A',
      $forecast->accuracy ? number_format($forecast->accuracy->bias * 100, 1) . '%' : 'N/A',
      $forecast->created_at->format('Y-m-d H:i:s')
    ];
  }
}
