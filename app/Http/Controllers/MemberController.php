<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MemberController extends Controller
{
    /**
     * Admin Dashboard
     */
    public function index(Request $request)
    {
        $query = Member::query();

        // 🔍 SEARCH
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->search . '%')
                  ->orWhere('facebook_name', 'like', '%' . $request->search . '%');
            });
        }

        // 🎯 FILTER
        if ($request->filter && $request->filter !== 'all') {
            $query->where('status', $request->filter);
        }

        // ✅ ORDER LIKE ATTENDANCE (FIT-0001 → FIT-0002 → LAST)
        $members = $query->orderBy('member_id', 'asc')->get();

        $membershipLabels = $this->membershipLabels();


        return view('dashboard', compact('members', 'membershipLabels'));
    }private function membershipLabels()
    {
        return [
            'unli_1_month' => '1 Month – ₱600',
            'unli_3_months' => '3 Months – ₱1,200',
            'unli_6_months' => '6 Months – ₱2,200',
            'pt_package_a' => 'PT Package A – 6 Sessions (₱1,200)',
            'pt_package_b' => 'PT Package B – 11 + 1 Free (₱2,200)',
            'pt_package_c' => 'PT Package C – 24 + 5 Free (₱4,800)',
            'boxing_package_a' => 'Boxing Package A – 6 Sessions (₱1,500)',
            'boxing_package_b' => 'Boxing Package B – 11 + 1 Free (₱2,700)',
            'boxing_package_c' => 'Boxing Package C – 24 + 5 Free (₱5,700)',
        ];
    }

    /**
     * Show registration form
     */
    public function create()
    {
        return view('member-register');
    }

    /**
     * Show ID
     */
    public function show($id)
    {
        $member = Member::findOrFail($id);
        return view('id', compact('member'));
    }

    /**
     * Store member
     */
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string',
            'facebook_name' => 'required|string',
            'email' => 'required|email|unique:members,email',
            'membership_package' => 'required|array|min:1|max:2',
            'membership_package.*' => 'string',
            'id_photo' => 'required|image',
        ]);

      $file = $request->file('id_photo');
      $filename = time() . '_' . $file->getClientOriginalName();
      $file->move(public_path('storage/id_photos'), $filename);
      $id_photo_path = 'id_photos/' . $filename;

        // GENERATE MEMBER ID
        $lastMember = Member::orderBy('id', 'desc')->first();
        $lastNumber = $lastMember ? intval(substr($lastMember->member_id, 4)) : 0;
        $memberID = 'FIT-' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        $packages = [
            'unli_1_month' => 30,
            'unli_3_months' => 90,
            'unli_6_months' => 180,
            'pt_package_a' => 20,
            'pt_package_b' => 60,
            'pt_package_c' => 150,
            'boxing_package_a' => 20,
            'boxing_package_b' => 60,
            'boxing_package_c' => 150,
        ];

        $selected = $request->membership_package;
        $mainMembership = $selected[0];
        $additionalMembership = $selected[1] ?? null;

        $validDays = $packages[$mainMembership];
        $startDate = Carbon::today();

        if (str_starts_with($mainMembership, 'unli_')) {
            $endDate = $startDate->copy()->addMonthNoOverflow()->endOfDay();
        } else {
            $endDate = $startDate->copy()->addDays($validDays)->endOfDay();
        }

        Member::create([
            'member_id' => $memberID,
            'full_name' => $request->full_name,
            'facebook_name' => $request->facebook_name,
            'email' => $request->email,
            'membership_type' => $mainMembership,
            'additional_membership' => $additionalMembership,
            'valid_days' => $validDays,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'id_photo' => $id_photo_path,
            'status' => 'Active',
        ]);

        return redirect()->route('member.success')
            ->with('status', 'You have successfully registered!');
    }

    /**
     * Show the edit form for a member
     */
    public function edit($id)
    {
        $member = Member::findOrFail($id);
        return view('edit-member', compact('member'));
    }

    /**
     * Update member
     */
    public function update(Request $request, $id)
    {
        $member = Member::findOrFail($id);

        $request->validate([
            'full_name' => 'required|string',
            'facebook_name' => 'required|string',
            'email' => 'required|email|unique:members,email,' . $member->id,
            'membership_package' => 'required|array|min:1|max:2',
            'membership_package.*' => 'string',
            'id_photo' => 'nullable|image',
        ]);

        $packages = [
            'unli_1_month' => 30,
            'unli_3_months' => 90,
            'unli_6_months' => 180,
            'pt_package_a' => 20,
            'pt_package_b' => 60,
            'pt_package_c' => 150,
            'boxing_package_a' => 20,
            'boxing_package_b' => 60,
            'boxing_package_c' => 150,
        ];

        $selected = $request->membership_package;
        $mainMembership = $selected[0];
        $additionalMembership = $selected[1] ?? null;

        $validDays = $packages[$mainMembership];
        $startDate = Carbon::today();

        if (str_starts_with($mainMembership, 'unli_')) {
            $endDate = $startDate->copy()->addMonthNoOverflow()->endOfDay();
        } else {
            $endDate = $startDate->copy()->addDays($validDays)->endOfDay();
        }

        // Update photo if uploaded
        $file = $request->file('id_photo');
    $filename = time() . '_' . $file->getClientOriginalName();
    $file->move(public_path('storage/id_photos'), $filename);

    $member->id_photo = 'id_photos/' . $filename;


        $member->update([
            'full_name' => $request->full_name,
            'facebook_name' => $request->facebook_name,
            'email' => $request->email,
            'membership_type' => $mainMembership,
            'additional_membership' => $additionalMembership,
            'valid_days' => $validDays,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        return redirect()->route('members.edit', $member->id)
            ->with('status', 'Member updated successfully!');
    }
}
