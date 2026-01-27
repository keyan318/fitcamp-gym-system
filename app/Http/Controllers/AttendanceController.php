<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberAttendance;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // QR scan
    public function mark($memberId)
    {
        $member = Member::where('member_id', $memberId)->firstOrFail();

        $today = Carbon::today();

        if (! $today->between($member->start_date, $member->end_date)) {
            return view('scan.expired', compact('member'));
        }

        MemberAttendance::firstOrCreate([
            'member_id' => $member->id,
            'date' => $today->toDateString(),
        ], [
            'status' => 'Present'
        ]);

        return view('scan.present', compact('member'));
    }

    // Calendar
    public function calendar($memberId)
    {
        $member = Member::findOrFail($memberId);

        $attendedDates = MemberAttendance::where('member_id', $memberId)
            ->pluck('date')
            ->toArray();

        return view('attendance.calendar', [
            'member' => $member,
            'startDate' => Carbon::parse($member->start_date),
            'endDate' => Carbon::parse($member->end_date),
            'attendedDates' => $attendedDates,
        ]);
    }
    // 🔹 ATTENDANCE LIST
    public function index()
    {
        $members = Member::all();
        return view('attendance.index', compact('members'));
    }

}
