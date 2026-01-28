<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->search . '%')
                  ->orWhere('facebook_name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filter && $request->filter !== 'all') {
            $query->where('status', $request->filter);
        }

        $members = $query->orderBy('member_id', 'asc')->get();

        return view('dashboard', [
            'members' => $members,
            'membershipLabels' => $this->membershipLabels(),
        ]);
    }

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

    public function create()
    {
        return view('member-register');
    }

    public function show($id)
    {
        $member = Member::findOrFail($id);
        return view('id', compact('member'));
    }

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

        // ✅ STORE TO SUPABASE S3
        $path = Storage::disk('s3')->put(
            'member-images',
            $request->file('id_photo'),
            'public'
        );

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

        $mainMembership = $request->membership_package[0];
        $additionalMembership = $request->membership_package[1] ?? null;

        $startDate = Carbon::today();
        $endDate = str_starts_with($mainMembership, 'unli_')
            ? $startDate->copy()->addMonthNoOverflow()->endOfDay()
            : $startDate->copy()->addDays($packages[$mainMembership])->endOfDay();

        Member::create([
            'member_id' => $memberID,
            'full_name' => $request->full_name,
            'facebook_name' => $request->facebook_name,
            'email' => $request->email,
            'membership_type' => $mainMembership,
            'additional_membership' => $additionalMembership,
            'valid_days' => $packages[$mainMembership],
            'start_date' => $startDate,
            'end_date' => $endDate,
            'id_photo' => $path, // ✅ S3 path
            'status' => 'Active',
        ]);

        return redirect()->route('member.success')
            ->with('status', 'You have successfully registered!');
    }

    public function edit($id)
    {
        $member = Member::findOrFail($id);
        return view('edit-member', compact('member'));
    }

    public function update(Request $request, $id)
    {
        $member = Member::findOrFail($id);

        if ($request->hasFile('id_photo')) {
            if ($member->id_photo) {
                Storage::disk('s3')->delete($member->id_photo);
            }

            $member->id_photo = Storage::disk('s3')->put(
                'member-images',
                $request->file('id_photo'),
                'public'
            );
        }

        $member->update($request->only([
            'full_name',
            'facebook_name',
            'email',
        ]));

        return back()->with('status', 'Member updated successfully!');
    }
}
