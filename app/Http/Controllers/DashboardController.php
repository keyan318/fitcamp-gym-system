<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberAttendance;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $totalMembers = Member::count();

        $activeMembers = Member::where('end_date', '>=', $today)->count();

        $expiredMembers = Member::where('end_date', '<', $today)->count();

        $attendancesToday = MemberAttendance::whereDate('created_at', $today)->count();

        // Attendance graph (last 7 days)
        $attendanceGraph = [
            'dates' => [],
            'counts' => []
        ];

        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);

            $attendanceGraph['dates'][] = $date->format('M d');
            $attendanceGraph['counts'][] = MemberAttendance::whereDate('created_at', $date)->count();
        }

        return view('admin.mainDashboard', compact(
            'totalMembers',
            'activeMembers',
            'expiredMembers',
            'attendancesToday',
            'attendanceGraph'
        ));
    }
}
