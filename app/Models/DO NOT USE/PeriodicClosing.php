<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PeriodicClosing;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    /**
     * Close financial period and generate report
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function closeFinancialPeriod(Request $request)
    {
        $closingDate = now(); // Automatically set to the current date
        $reportType = 'Profit and Loss'; // Example report type, you can make this dynamic based on your needs

        // Generate the report and save it
        $filePath = $this->generateReport($reportType, $closingDate);

        // Store information in the periodic_closings table
        $periodicClosing = PeriodicClosing::create([
            'closing_date' => $closingDate,
            'report_type' => $reportType,
            'file_path' => $filePath,
        ]);

        return response()->json(['message' => 'Financial period closed successfully!', 'periodic_closing' => $periodicClosing]);
    }

    /**
     * Simulate report generation and store it in the storage folder.
     *
     * @param string $reportType
     * @param \Illuminate\Support\Carbon $closingDate
     * @return string File path of the generated report
     */
    private function generateReport($reportType, $closingDate)
    {
        // For demonstration purposes, we'll create a dummy report file
        $fileName = 'report_' . $closingDate->format('Y_m_d') . '.txt';
        $filePath = 'reports/' . $fileName;

        // Dummy content for the report
        $reportContent = "Report Type: {$reportType}\nClosing Date: {$closingDate->toDateString()}\n--- Report Content Here ---";

        // Save the report file to the storage folder (storage/app/reports)
        Storage::put($filePath, $reportContent);

        return $filePath;
    }
}