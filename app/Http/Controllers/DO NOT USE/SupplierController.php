<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\SupplierContract;
use App\Models\SupplierEvaluation;
use App\Models\SupplierNotification;
use App\Models\SupplierDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SupplierController extends Controller
{
  public function index()
  {
    $suppliers = Supplier::with(['contracts' => function ($query) {
      $query->where('status', 'active');
    }])->get();

    return view('suppliers.index', compact('suppliers'));
  }

  public function show(Supplier $supplier)
  {
    $supplier->load([
      'contracts' => function ($query) {
        $query->where('status', 'active');
      },
      'evaluations' => function ($query) {
        $query->latest('evaluation_date')->take(10);
      },
      'products' => function ($query) {
        $query->where('is_active', true);
      },
      'documents',
      'notifications' => function ($query) {
        $query->whereNull('read_at')->orderBy('priority', 'desc');
      }
    ]);

    $performanceMetrics = [
      'quality' => $supplier->quality_score,
      'delivery' => $supplier->delivery_score,
      'service' => $supplier->service_score,
      'overall' => round(($supplier->quality_score + $supplier->delivery_score + $supplier->service_score) / 3)
    ];

    return view('suppliers.show', compact('supplier', 'performanceMetrics'));
  }

  public function create()
  {
    return view('suppliers.create');
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'contact_person' => 'required|string|max:255',
      'email' => 'required|email|max:255',
      'phone' => 'required|string|max:50',
      'address' => 'required|string|max:255',
      'city' => 'required|string|max:255',
      'country' => 'required|string|max:255',
      'postal_code' => 'required|string|max:20',
      'tax_number' => 'nullable|string|max:50',
      'registration_number' => 'nullable|string|max:50',
      'classification' => 'required|string|in:strategic,tactical,operational',
      'is_critical' => 'boolean',
      'notes' => 'nullable|string'
    ]);

    $supplier = Supplier::create($validated);

    return redirect()->route('suppliers.show', $supplier)
      ->with('success', 'Leverancier succesvol toegevoegd.');
  }

  public function edit(Supplier $supplier)
  {
    return view('suppliers.edit', compact('supplier'));
  }

  public function update(Request $request, Supplier $supplier)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'contact_person' => 'required|string|max:255',
      'email' => 'required|email|max:255',
      'phone' => 'required|string|max:50',
      'address' => 'required|string|max:255',
      'city' => 'required|string|max:255',
      'country' => 'required|string|max:255',
      'postal_code' => 'required|string|max:20',
      'tax_number' => 'nullable|string|max:50',
      'registration_number' => 'nullable|string|max:50',
      'classification' => 'required|string|in:strategic,tactical,operational',
      'is_critical' => 'boolean',
      'notes' => 'nullable|string'
    ]);

    $supplier->update($validated);

    return redirect()->route('suppliers.show', $supplier)
      ->with('success', 'Leverancier succesvol bijgewerkt.');
  }

  public function evaluate(Request $request, Supplier $supplier)
  {
    $validated = $request->validate([
      'delivery_time_rating' => 'required|numeric|min:1|max:5',
      'quality_rating' => 'required|numeric|min:1|max:5',
      'communication_rating' => 'required|numeric|min:1|max:5',
      'price_rating' => 'required|numeric|min:1|max:5',
      'comments' => 'nullable|string',
      'order_reference' => 'required|string|max:50'
    ]);

    $evaluation = new SupplierEvaluation($validated);
    $evaluation->user_id = Auth::id();
    $evaluation->evaluation_date = now();

    $supplier->evaluations()->save($evaluation);

    // Check if performance is below threshold and create notification if needed
    $threshold = 60; // 60% threshold for performance alerts
    foreach (['quality', 'delivery', 'service'] as $metric) {
      $score = $supplier->{$metric . '_score'};
      if ($score < $threshold) {
        SupplierNotification::createPerformanceAlertNotification(
          $supplier,
          $metric,
          $score,
          $threshold
        );
      }
    }

    return redirect()->route('suppliers.show', $supplier)
      ->with('success', 'Leveranciersbeoordeling succesvol toegevoegd.');
  }

  public function contractsOverview()
  {
    $contracts = SupplierContract::with('supplier')
      ->orderBy('end_date', 'desc')
      ->paginate(10);

    return view('suppliers.contracts', compact('contracts'));
  }

  public function contracts(Supplier $supplier)
  {
    $contracts = $supplier->contracts()
      ->orderBy('end_date', 'desc')
      ->paginate(10);

    return view('suppliers.contracts', compact('supplier', 'contracts'));
  }

  public function storeContract(Request $request, Supplier $supplier)
  {
    $validated = $request->validate([
      'contract_number' => 'required|string|max:50|unique:supplier_contracts',
      'title' => 'required|string|max:255',
      'description' => 'nullable|string',
      'start_date' => 'required|date',
      'end_date' => 'required|date|after:start_date',
      'value' => 'required|numeric|min:0',
      'currency' => 'required|string|size:3',
      'terms_conditions' => 'required|string',
      'payment_terms' => 'required|string',
      'payment_days' => 'required|integer|min:0',
      'auto_renewal' => 'boolean',
      'renewal_notice_days' => 'required|integer|min:0'
    ]);

    $contract = new SupplierContract($validated);
    $contract->status = 'active';

    $supplier->contracts()->save($contract);

    return redirect()->route('suppliers.contracts', $supplier)
      ->with('success', 'Contract succesvol toegevoegd.');
  }

  public function performanceOverview()
  {
    $suppliers = Supplier::with(['evaluations' => function ($query) {
      $query->latest('evaluation_date')->take(5);
    }])->get();

    $performanceData = $suppliers->map(function ($supplier) {
      return [
        'supplier' => $supplier,
        'metrics' => [
          'quality' => $supplier->quality_score,
          'delivery' => $supplier->delivery_score,
          'service' => $supplier->service_score,
          'overall' => round(($supplier->quality_score + $supplier->delivery_score + $supplier->service_score) / 3)
        ],
        'evaluations' => $supplier->evaluations
      ];
    });

    return view('suppliers.performance', compact('performanceData'));
  }

  public function performance(Supplier $supplier)
  {
    $evaluations = $supplier->evaluations()
      ->latest('evaluation_date')
      ->paginate(10);

    $performanceData = [
      'quality' => [
        'current' => $supplier->quality_score,
        'trend' => $evaluations->pluck('quality_rating')->avg() * 20
      ],
      'delivery' => [
        'current' => $supplier->delivery_score,
        'trend' => $evaluations->pluck('delivery_time_rating')->avg() * 20
      ],
      'service' => [
        'current' => $supplier->service_score,
        'trend' => $evaluations->pluck('communication_rating')->avg() * 20
      ]
    ];

    return view('suppliers.performance', compact('supplier', 'evaluations', 'performanceData'));
  }

  public function documentsOverview()
  {
    $documents = DB::table('supplier_documents')
      ->join('suppliers', 'supplier_documents.supplier_id', '=', 'suppliers.id')
      ->select('supplier_documents.*', 'suppliers.name as supplier_name')
      ->orderBy('created_at', 'desc')
      ->paginate(10);

    return view('suppliers.documents', compact('documents'));
  }

  public function documents(Supplier $supplier)
  {
    $documents = $supplier->documents()
      ->orderBy('created_at', 'desc')
      ->paginate(10);

    return view('suppliers.documents', compact('supplier', 'documents'));
  }

  public function storeDocument(Request $request, Supplier $supplier)
  {
    $validated = $request->validate([
      'title' => 'required|string|max:255',
      'document_type' => 'required|string|max:50',
      'description' => 'nullable|string',
      'valid_until' => 'nullable|date',
      'document' => 'required|file|max:10240' // 10MB max
    ]);

    $path = $request->file('document')->store('supplier-documents');

    $document = new SupplierDocument([
      'title' => $validated['title'],
      'document_type' => $validated['document_type'],
      'description' => $validated['description'],
      'valid_until' => $validated['valid_until'],
      'file_path' => $path
    ]);

    $supplier->documents()->save($document);

    return redirect()->route('suppliers.documents', $supplier)
      ->with('success', 'Document successfully uploaded.');
  }

  public function destroyDocument(Supplier $supplier, SupplierDocument $document)
  {
    if ($document->supplier_id !== $supplier->id) {
      abort(403);
    }

    Storage::delete($document->file_path);
    $document->delete();

    return redirect()->route('suppliers.documents', $supplier)
      ->with('success', 'Document successfully deleted.');
  }

  public function notificationsOverview()
  {
    $notifications = SupplierNotification::with('supplier')
      ->whereNull('read_at')
      ->orderBy('priority', 'desc')
      ->orderBy('created_at', 'desc')
      ->paginate(10);

    return view('suppliers.notifications', compact('notifications'));
  }

  public function notifications(Supplier $supplier)
  {
    $notifications = $supplier->notifications()
      ->orderBy('created_at', 'desc')
      ->paginate(10);

    return view('suppliers.notifications', compact('supplier', 'notifications'));
  }

  public function markNotificationAsRead(Supplier $supplier, SupplierNotification $notification)
  {
    if ($notification->supplier_id === $supplier->id) {
      $notification->markAsRead();
    }

    return redirect()->back();
  }

  public function report(Request $request)
  {
    $suppliers = Supplier::query()
      ->when($request->filled('classification'), function ($query) use ($request) {
        $query->where('classification', $request->classification);
      })
      ->when($request->filled('is_critical'), function ($query) {
        $query->where('is_critical', true);
      })
      ->when($request->filled('performance_threshold'), function ($query) use ($request) {
        $threshold = $request->performance_threshold;
        $query->whereHas('evaluations', function ($q) use ($threshold) {
          $q->where('overall_rating', '>=', $threshold);
        });
      })
      ->with(['contracts', 'evaluations'])
      ->get();

    $report = [
      'total_suppliers' => $suppliers->count(),
      'active_contracts' => $suppliers->sum(function ($supplier) {
        return $supplier->contracts->where('status', 'active')->count();
      }),
      'expiring_contracts' => $suppliers->sum(function ($supplier) {
        return $supplier->getExpiringContracts()->count();
      }),
      'average_performance' => round($suppliers->avg(function ($supplier) {
        return ($supplier->quality_score + $supplier->delivery_score + $supplier->service_score) / 3;
      })),
      'suppliers_by_classification' => $suppliers->groupBy('classification')->map->count(),
      'critical_suppliers' => $suppliers->where('is_critical', true)->count()
    ];

    return view('suppliers.report', compact('report', 'suppliers'));
  }

  public function compareSuppliers(Request $request)
  {
    $supplierIds = $request->input('supplier_ids', []);
    $suppliers = Supplier::whereIn('id', $supplierIds)
      ->with(['evaluations', 'contracts'])
      ->get();

    $comparison = [];
    foreach ($suppliers as $supplier) {
      $comparison[$supplier->id] = [
        'name' => $supplier->name,
        'performance' => [
          'quality' => $supplier->quality_score,
          'delivery' => $supplier->delivery_score,
          'service' => $supplier->service_score,
          'overall' => round(($supplier->quality_score + $supplier->delivery_score + $supplier->service_score) / 3)
        ],
        'active_contracts' => $supplier->getActiveContracts()->count(),
        'total_evaluations' => $supplier->evaluations->count(),
        'average_rating' => $supplier->getAverageRating(),
        'is_critical' => $supplier->is_critical,
        'classification' => $supplier->classification
      ];
    }

    return view('suppliers.compare', compact('comparison'));
  }
}
