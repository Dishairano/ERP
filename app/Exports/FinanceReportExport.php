<?php

namespace App\Exports;

use App\Models\CoreFinanceReportModal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FinanceReportExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
  protected $report;
  protected $data;

  /**
   * Create a new export instance.
   */
  public function __construct(CoreFinanceReportModal $report, array $data)
  {
    $this->report = $report;
    $this->data = $data;
  }

  /**
   * @return \Illuminate\Support\Collection
   */
  public function collection()
  {
    return collect($this->data);
  }

  /**
   * @return array
   */
  public function headings(): array
  {
    $headings = ['Section', 'Account', 'Amount'];

    if ($this->report->comparison_type !== 'none') {
      $headings[] = 'Comparison Amount';
      if ($this->report->show_variances) {
        $headings[] = 'Variance';
      }
      if ($this->report->show_percentages) {
        $headings[] = 'Variance %';
      }
    }

    return $headings;
  }

  /**
   * @param mixed $row
   * @return array
   */
  public function map($row): array
  {
    $mapped = [
      $row['section'] ?? '',
      $row['account'] ?? '',
      $row['amount'] ?? 0
    ];

    if ($this->report->comparison_type !== 'none') {
      $mapped[] = $row['comparison_amount'] ?? 0;
      if ($this->report->show_variances) {
        $mapped[] = ($row['amount'] ?? 0) - ($row['comparison_amount'] ?? 0);
      }
      if ($this->report->show_percentages) {
        $comparisonAmount = $row['comparison_amount'] ?? 0;
        $mapped[] = $comparisonAmount != 0 ?
          (($row['amount'] ?? 0) - $comparisonAmount) / abs($comparisonAmount) * 100 :
          0;
      }
    }

    return $mapped;
  }

  /**
   * @return string
   */
  public function title(): string
  {
    return $this->report->name;
  }

  /**
   * @param Worksheet $sheet
   * @return array
   */
  public function styles(Worksheet $sheet)
  {
    return [
      1 => ['font' => ['bold' => true]],
      'A' => ['font' => ['bold' => true]],
    ];
  }
}
