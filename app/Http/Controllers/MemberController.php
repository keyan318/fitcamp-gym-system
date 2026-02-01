<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MemberController extends Controller
{
    /**
     * Display Member Dashboard / List
     */
    public function index(Request $request)
    {
        $query = Member::query();

        // Search by full_name or facebook_name
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->search . '%')
                  ->orWhere('facebook_name', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by status
        if ($request->filter && $request->filter !== 'all') {
            $query->where('status', $request->filter);
        }

        $members = $query->orderBy('member_id', 'asc')->get();
        $membershipLabels = $this->membershipLabels();

        return view('dashboard', compact('members', 'membershipLabels'));
    }

    /**
     * Membership Labels
     */
    private function membershipLabels()
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
     * Show the registration form
     */
    public function create()
    {
        return view('member-register');
    }

    /**
     * Show member digital ID
     */
    public function show($id)
    {
        $member = Member::findOrFail($id);
        return view('id', compact('member'));
    }

    /**
     * Show edit form for member
     */
    public function edit($id)
    {
        $member = Member::findOrFail($id);
        $membershipLabels = $this->membershipLabels();
        return view('edit-member', compact('member', 'membershipLabels'));
    }

    /**
     * Store a New Member
     */
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string',
            'facebook_name' => 'required|string',
            'email' => 'required|email|unique:members,email',
            'membership_package' => 'required|array|min:1|max:2',
            'membership_package.*' => 'string',
            'id_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Upload photo to Supabase S3
        $file = $request->file('id_photo');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = 'id_photos/' . $filename;

        // Upload to S3 (Supabase) - Use putFileAs for cleaner upload
        Storage::disk('s3')->putFileAs('id_photos', $file, $filename, 'public');

        // Generate Member ID
        $lastMember = Member::orderBy('id', 'desc')->first();
        $lastNumber = $lastMember ? intval(substr($lastMember->member_id, 4)) : 0;
        $memberID = 'FIT-' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        // Membership days mapping
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
        $endDate = str_starts_with($mainMembership, 'unli_')
            ? $startDate->copy()->addMonthNoOverflow()->endOfDay()
            : $startDate->copy()->addDays($validDays)->endOfDay();

        // Create Member
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
            'id_photo' => $path,
            'status' => 'Active',
        ]);

        return redirect()->route('member.success')
            ->with('status', 'You have successfully registered!');
    }

    /**
     * Update Existing Member
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
            'id_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Upload new photo if exists (Supabase S3)
        if ($request->hasFile('id_photo')) {
            // Delete old photo from Supabase
            if ($member->id_photo && Storage::disk('s3')->exists($member->id_photo)) {
                Storage::disk('s3')->delete($member->id_photo);
            }

            $file = $request->file('id_photo');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = 'id_photos/' . $filename;

            // Upload to S3 (Supabase)
            Storage::disk('s3')->putFileAs('id_photos', $file, $filename, 'public');
            $member->id_photo = $path;
        }

        $member->update([
            'full_name' => $request->full_name,
            'facebook_name' => $request->facebook_name,
            'email' => $request->email,
        ]);

        return redirect()->route('members.edit', $member->id)
            ->with('status', 'Member updated successfully!');
    }

    /**
     * Delete member
     */
    public function destroy($id)
    {
        $member = Member::findOrFail($id);

        // Delete photo from Supabase S3
        if ($member->id_photo && Storage::disk('s3')->exists($member->id_photo)) {
            Storage::disk('s3')->delete($member->id_photo);
        }

        // Delete member record
        $member->delete();

        return redirect()->route('attendance.index')
            ->with('status', 'Member deleted successfully!');
    }

    /**
     * Get public URL for member photo
     */
    public static function getPhotoUrl($path)
    {
        if (!$path) {
            return asset('images/default.png');
        }

        // Build the full public URL manually
        $baseUrl = config('filesystems.disks.s3.url');
        return $baseUrl . '/' . $path;
    }
}
