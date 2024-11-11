<?php

namespace App\Http\Controllers;

use App\Models\DataAnalysisConfig;
use App\Models\DataAnalysisResult;
use App\Models\DataVisualization;
use App\Models\DataDashboard;
use App\Models\DashboardComponent;
use App\Models\DataExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class DataAnalysisController extends Controller
{
  public function index()
  {
    $configs = DataAnalysisConfig::where('user_id', Auth::id())
      ->orderBy('created_at', 'desc')
      ->get();

    return view('data-analysis.index', compact('configs'));
  }

  public function create()
  {
    return view('data-analysis.create');
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'type' => 'required|string',
      'criteria' => 'required|array'
    ]);

    $config = DataAnalysisConfig::create([
      'name' => $validated['name'],
      'type' => $validated['type'],
      'criteria' => $validated['criteria'],
      'user_id' => Auth::id()
    ]);

    return redirect()->route('data-analysis.show', $config)
      ->with('success', 'Analysis configuration created successfully.');
  }

  public function show(DataAnalysisConfig $config)
  {
    $results = $config->results()->latest()->get();
    $visualizations = $config->visualizations;

    return view('data-analysis.show', compact('config', 'results', 'visualizations'));
  }

  public function analyze(DataAnalysisConfig $config)
  {
    try {
      // Perform the analysis based on config type and criteria
      $data = $this->performAnalysis($config);

      $result = DataAnalysisResult::create([
        'config_id' => $config->id,
        'result_data' => $data,
        'analyzed_at' => now()
      ]);

      return redirect()->route('data-analysis.show', $config)
        ->with('success', 'Analysis completed successfully.');
    } catch (\Exception $e) {
      return redirect()->route('data-analysis.show', $config)
        ->with('error', 'Error performing analysis: ' . $e->getMessage());
    }
  }

  public function export(DataAnalysisResult $result, Request $request)
  {
    $format = $request->input('format', 'excel');

    // Generate export file
    $filePath = storage_path('app/exports/' . uniqid() . '.' . $format);

    // Create export record
    $export = DataExport::create([
      'result_id' => $result->id,
      'format' => $format,
      'file_path' => $filePath
    ]);

    // Generate the export file based on format
    switch ($format) {
      case 'excel':
        $this->generateExcelExport($result, $filePath);
        break;
      case 'csv':
        $this->generateCsvExport($result, $filePath);
        break;
      case 'pdf':
        $this->generatePdfExport($result, $filePath);
        break;
    }

    return response()->download($filePath);
  }

  public function dashboard()
  {
    $dashboards = DataDashboard::where('user_id', Auth::id())
      ->orWhere('is_public', true)
      ->with(['components.visualization'])
      ->get();

    $visualizations = DataVisualization::all();

    return view('data-analysis.dashboard', compact('dashboards', 'visualizations'));
  }

  public function addWidget(Request $request)
  {
    $validated = $request->validate([
      'dashboard_id' => 'required|exists:data_dashboards,id',
      'visualization_id' => 'required|exists:data_visualizations,id',
      'position_x' => 'required|integer|min:1',
      'position_y' => 'required|integer|min:1',
      'width' => 'required|integer|min:1|max:12',
      'height' => 'required|integer|min:1'
    ]);

    DashboardComponent::create($validated);

    return redirect()->route('data-analysis.dashboard')
      ->with('success', 'Widget added successfully.');
  }

  public function updateWidgetPosition(DashboardComponent $component, Request $request)
  {
    $validated = $request->validate([
      'position_x' => 'required|integer|min:1',
      'position_y' => 'required|integer|min:1'
    ]);

    $component->update($validated);

    return response()->json(['success' => true]);
  }

  protected function performAnalysis(DataAnalysisConfig $config)
  {
    $data = [];

    switch ($config->type) {
      case 'sales':
        if (!Schema::hasTable('sales')) {
          throw new \Exception('Sales table does not exist. Please run migrations first.');
        }
        $data = $this->analyzeSalesData($config->criteria);
        break;
      case 'finance':
        if (!Schema::hasTable('expenses') || !Schema::hasTable('cost_categories')) {
          throw new \Exception('Required tables do not exist. Please run migrations first.');
        }
        $data = $this->analyzeFinancialData($config->criteria);
        break;
      case 'inventory':
        if (!Schema::hasTable('products') || !Schema::hasTable('supplier_products')) {
          throw new \Exception('Required tables do not exist. Please run migrations first.');
        }
        $data = $this->analyzeInventoryData($config->criteria);
        break;
      case 'hr':
        if (!Schema::hasTable('payrolls') || !Schema::hasTable('departments')) {
          throw new \Exception('Required tables do not exist. Please run migrations first.');
        }
        $data = $this->analyzeHRData($config->criteria);
        break;
    }

    return $data;
  }

  protected function analyzeSalesData($criteria)
  {
    return DB::table('sales')
      ->when(isset($criteria['date_range']), function ($query) use ($criteria) {
        return $query->whereBetween('date', $criteria['date_range']);
      })
      ->when(isset($criteria['region']), function ($query) use ($criteria) {
        return $query->where('region', $criteria['region']);
      })
      ->select(
        'region',
        'product_id',
        DB::raw('COUNT(*) as total_sales'),
        DB::raw('SUM(amount) as revenue'),
        DB::raw('AVG(amount) as average_sale')
      )
      ->groupBy('region', 'product_id')
      ->get();
  }

  protected function analyzeFinancialData($criteria)
  {
    return DB::table('expenses')
      ->join('cost_categories', 'expenses.category_id', '=', 'cost_categories.id')
      ->when(isset($criteria['date_range']), function ($query) use ($criteria) {
        return $query->whereBetween('date', $criteria['date_range']);
      })
      ->when(isset($criteria['department']), function ($query) use ($criteria) {
        return $query->where('department_id', $criteria['department']);
      })
      ->select(
        'cost_categories.name as category',
        DB::raw('SUM(amount) as total_expense'),
        DB::raw('COUNT(*) as transaction_count')
      )
      ->groupBy('cost_categories.name')
      ->get();
  }

  protected function analyzeInventoryData($criteria)
  {
    return DB::table('products')
      ->join('supplier_products', 'products.id', '=', 'supplier_products.product_id')
      ->when(isset($criteria['supplier']), function ($query) use ($criteria) {
        return $query->where('supplier_id', $criteria['supplier']);
      })
      ->select(
        'products.name',
        'products.sku',
        DB::raw('SUM(quantity) as total_stock'),
        DB::raw('AVG(price) as average_price')
      )
      ->groupBy('products.name', 'products.sku')
      ->get();
  }

  protected function analyzeHRData($criteria)
  {
    return DB::table('payrolls')
      ->join('departments', 'payrolls.department_id', '=', 'departments.id')
      ->when(isset($criteria['date_range']), function ($query) use ($criteria) {
        return $query->whereBetween('date', $criteria['date_range']);
      })
      ->select(
        'departments.name',
        DB::raw('COUNT(DISTINCT employee_id) as employee_count'),
        DB::raw('SUM(salary) as total_salary'),
        DB::raw('AVG(salary) as average_salary')
      )
      ->groupBy('departments.name')
      ->get();
  }

  protected function generateExcelExport(DataAnalysisResult $result, string $filePath)
  {
    // Implementation for Excel export
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Add headers
    $headers = array_keys((array) $result->result_data[0]);
    foreach ($headers as $col => $header) {
      $sheet->setCellValueByColumnAndRow($col + 1, 1, ucwords(str_replace('_', ' ', $header)));
    }

    // Add data
    foreach ($result->result_data as $row => $data) {
      foreach ((array) $data as $col => $value) {
        $sheet->setCellValueByColumnAndRow($col + 1, $row + 2, $value);
      }
    }

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save($filePath);
  }

  protected function generateCsvExport(DataAnalysisResult $result, string $filePath)
  {
    // Implementation for CSV export
    $file = fopen($filePath, 'w');

    // Add headers
    $headers = array_keys((array) $result->result_data[0]);
    fputcsv($file, array_map(function ($header) {
      return ucwords(str_replace('_', ' ', $header));
    }, $headers));

    // Add data
    foreach ($result->result_data as $data) {
      fputcsv($file, (array) $data);
    }

    fclose($file);
  }

  protected function generatePdfExport(DataAnalysisResult $result, string $filePath)
  {
    // Implementation for PDF export
    $pdf = new \Dompdf\Dompdf();

    $html = '<html><body>';
    $html .= '<h1>' . $result->config->name . ' Analysis Results</h1>';
    $html .= '<table border="1" cellpadding="5">';

    // Add headers
    $headers = array_keys((array) $result->result_data[0]);
    $html .= '<tr>';
    foreach ($headers as $header) {
      $html .= '<th>' . ucwords(str_replace('_', ' ', $header)) . '</th>';
    }
    $html .= '</tr>';

    // Add data
    foreach ($result->result_data as $data) {
      $html .= '<tr>';
      foreach ((array) $data as $value) {
        $html .= '<td>' . $value . '</td>';
      }
      $html .= '</tr>';
    }

    $html .= '</table></body></html>';

    $pdf->loadHtml($html);
    $pdf->render();
    file_put_contents($filePath, $pdf->output());
  }
}
