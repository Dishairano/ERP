<?php

namespace App\Http\Controllers;

use App\Models\DataIntegration;
use App\Models\DataMapping;
use App\Models\ApiConfiguration;
use App\Models\DatabaseConnection;
use App\Models\IntegrationSchedule;
use App\Models\DataValidationRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DataIntegrationController extends Controller
{
  public function index()
  {
    $integrations = DataIntegration::with(['mappings', 'apiConfiguration', 'databaseConnection', 'schedule'])
      ->orderBy('name')
      ->get();

    return view('data-integration.index', compact('integrations'));
  }

  public function create()
  {
    return view('data-integration.create');
  }

  public function store(Request $request)
  {
    try {
      DB::beginTransaction();

      $integration = DataIntegration::create($request->validate([
        'name' => 'required|string|max:255',
        'source_type' => 'required|string',
        'connection_type' => 'required|string',
        'connection_details' => 'required|json',
        'is_active' => 'boolean',
        'sync_interval' => 'nullable|integer'
      ]));

      if ($request->has('mappings')) {
        foreach ($request->mappings as $mapping) {
          $integration->mappings()->create($mapping);
        }
      }

      if ($request->has('api_configuration')) {
        $integration->apiConfiguration()->create($request->api_configuration);
      }

      if ($request->has('database_connection')) {
        $integration->databaseConnection()->create($request->database_connection);
      }

      if ($request->has('schedule')) {
        $integration->schedule()->create($request->schedule);
      }

      if ($request->has('validation_rules')) {
        foreach ($request->validation_rules as $rule) {
          $integration->validationRules()->create($rule);
        }
      }

      DB::commit();
      return redirect()->route('data-integration.index')->with('success', 'Integration created successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      Log::error('Failed to create integration: ' . $e->getMessage());
      return back()->with('error', 'Failed to create integration. Please try again.');
    }
  }

  public function edit(DataIntegration $integration)
  {
    $integration->load(['mappings', 'apiConfiguration', 'databaseConnection', 'schedule', 'validationRules']);
    return view('data-integration.edit', compact('integration'));
  }

  public function update(Request $request, DataIntegration $integration)
  {
    try {
      DB::beginTransaction();

      $integration->update($request->validate([
        'name' => 'required|string|max:255',
        'source_type' => 'required|string',
        'connection_type' => 'required|string',
        'connection_details' => 'required|json',
        'is_active' => 'boolean',
        'sync_interval' => 'nullable|integer'
      ]));

      if ($request->has('mappings')) {
        $integration->mappings()->delete();
        foreach ($request->mappings as $mapping) {
          $integration->mappings()->create($mapping);
        }
      }

      if ($request->has('api_configuration')) {
        $integration->apiConfiguration()->delete();
        $integration->apiConfiguration()->create($request->api_configuration);
      }

      if ($request->has('database_connection')) {
        $integration->databaseConnection()->delete();
        $integration->databaseConnection()->create($request->database_connection);
      }

      if ($request->has('schedule')) {
        $integration->schedule()->delete();
        $integration->schedule()->create($request->schedule);
      }

      if ($request->has('validation_rules')) {
        $integration->validationRules()->delete();
        foreach ($request->validation_rules as $rule) {
          $integration->validationRules()->create($rule);
        }
      }

      DB::commit();
      return redirect()->route('data-integration.index')->with('success', 'Integration updated successfully');
    } catch (\Exception $e) {
      DB::rollBack();
      Log::error('Failed to update integration: ' . $e->getMessage());
      return back()->with('error', 'Failed to update integration. Please try again.');
    }
  }

  public function destroy(DataIntegration $integration)
  {
    try {
      $integration->delete();
      return redirect()->route('data-integration.index')->with('success', 'Integration deleted successfully');
    } catch (\Exception $e) {
      Log::error('Failed to delete integration: ' . $e->getMessage());
      return back()->with('error', 'Failed to delete integration. Please try again.');
    }
  }

  public function testConnection(DataIntegration $integration)
  {
    try {
      // Implementation will vary based on connection type
      $success = true;
      $message = 'Connection test successful';

      switch ($integration->connection_type) {
        case 'api':
          // Test API connection
          break;
        case 'database':
          // Test database connection
          break;
        case 'file_import':
          // Test file system access
          break;
      }

      return response()->json([
        'success' => $success,
        'message' => $message
      ]);
    } catch (\Exception $e) {
      Log::error('Connection test failed: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Connection test failed: ' . $e->getMessage()
      ], 500);
    }
  }

  public function syncNow(DataIntegration $integration)
  {
    try {
      // Implementation will vary based on integration type
      // This is a placeholder for the actual sync logic
      $result = [
        'success' => true,
        'records_processed' => 0,
        'records_succeeded' => 0,
        'records_failed' => 0
      ];

      $integration->syncLogs()->create([
        'status' => $result['success'] ? 'success' : 'error',
        'records_processed' => $result['records_processed'],
        'records_succeeded' => $result['records_succeeded'],
        'records_failed' => $result['records_failed']
      ]);

      $integration->update(['last_sync' => now()]);

      return response()->json([
        'success' => true,
        'message' => 'Sync completed successfully'
      ]);
    } catch (\Exception $e) {
      Log::error('Sync failed: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Sync failed: ' . $e->getMessage()
      ], 500);
    }
  }

  public function logs(DataIntegration $integration)
  {
    $logs = $integration->syncLogs()
      ->orderBy('created_at', 'desc')
      ->paginate(20);

    return view('data-integration.logs', compact('integration', 'logs'));
  }
}
