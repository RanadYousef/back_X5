<?php

namespace App\Http\Controllers;

use App\Http\Request\ReportRequest;
use App\Models\Borrowing;
use Illuminate\Support\Facades\Log;
use Exception;

class ReportController extends Controller
{
    public function
    generateReport(ReportRequest $request)
    {
        try {
            $validated = $request->validated();

            $reports = Borrowing::whereBetween('borrowed_at', [
                $validated['start_date'],
                $validated['end_date']
            ])
            ->with(['user','book'])
            ->get();
            return view('reports.show',compact('reports'));
            
        } catch (Exception $e) {
            Log::error('Error generating report: ' . $e->getMessage());

            return back()->withErrors(['error' => 'An error occurred while generating the report. Please try again later.']);
        }
    }
}
