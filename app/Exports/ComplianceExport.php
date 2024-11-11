<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class ComplianceExport implements FromCollection, WithHeadings
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
    if ($this->data->isEmpty()) {
      return [];
    }

    return array_keys($this->data->first()->toArray());
  }
}
