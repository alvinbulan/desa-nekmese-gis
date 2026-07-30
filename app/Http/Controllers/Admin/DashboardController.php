<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Facility;
use App\Models\Regulation;

class DashboardController extends Controller
{
    public function index()
    {
        $totalFacilities = Facility::count();
        $totalAnnouncements = Announcement::count();
        $totalRegulations = Regulation::count();
        $recentFacilities = Facility::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalFacilities', 'totalAnnouncements', 'totalRegulations', 'recentFacilities'
        ));
    }
}
