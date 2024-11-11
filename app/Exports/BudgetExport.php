<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class BudgetExport implements FromCollection, WithHeadings
{
  protected $data;

  public function __construct($data)
  {
    $this->data = $data;
  }

  public function collection()
  {
    return new Collection($this->data);
  }

  public function headings(): array
  {
    return [
      'category',
      'planned',
      'actual',
      'variance',
      'spent_percentage',
      'department',
      'project',
      'cost_category'
    ];
  }
}
