<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
use App\Models\Task;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    public function generate()
    {
        // Get statistics or data for the report
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();
        $totalTasks = Task::count();
        $totalPoints = User::sum('points');

        $data = [
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'totalTasks' => $totalTasks,
            'totalPoints' => $totalPoints,
        ];

        // Load the Blade view and generate the PDF
        $pdf = Pdf::loadView('admin.reports.pdf', $data);

        // Return the PDF as a download
        return $pdf->download('CommunityTAP-Admin-Report.pdf');
    }
}
