<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScanQrController extends Controller
{
    /**
     * Show the QR scanner page
     */
    public function index()
    {
        return view('scan.qr');
    }

    /**
     * QR scanned → forward to attendance logic
     */
    public function markAttendance($memberId)
    {
        // Forward QR result to AttendanceController
        return redirect()->route('scan.present', $memberId);
    }

    /**
     * Membership expired page
     */
    public function expired()
    {
        return view('scan.expired');
    }
}
