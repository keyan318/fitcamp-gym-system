<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member; // Make sure this model exists
use Illuminate\Support\Str; // Required if you use Str::slug for filenames
use Illuminate\Support\Facades\Storage;
use function Ramsey\Uuid\v1;

class MemberController extends Controller
{
    /**
     * Admin Dashboard: Display a listing of the members.
     * Fetches all members and passes them to the 'admin' view.
     */
   public function index(Request $request)
{
    // Start query
    $members = Member::query();

    // SEARCH
    if ($request->search) {
        $members->where(function($q) use ($request) {
            $q->where('full_name', 'like', '%' . $request->search . '%')
              ->orWhere('facebook_name', 'like', '%' . $request->search . '%');
        });
    }

    // FILTER (Active / Expired)
    if ($request->filter && $request->filter != 'all') {
        $members->where('status', $request->filter);
    }

    // Order newest first
    $members = $members->orderBy('created_at', 'desc')->get();

    return view('admin', compact('members'));
}


    /**
     * Show registration form: Display the member registration form.
     */
    public function create()
    {
        return view('member-register');
    }

    /**
     * Handle form submission: Store a newly created member in storage.
     */

    public function show($id){
        $member=Member::findOrFail(($id));
        return view('profile', compact('member'));
    }


    public function store(Request $request)
    {
        // 1. VALIDATION
        $request->validate([
            'full_name' => 'required|string|max:255',
            'facebook_name' => 'required|string|max:255',
            // Ensure email is unique in the 'members' table
            'email' => 'required|email|unique:members,email|max:255',
            'membership_plan' => 'required|in:monthly,3-months,6-months',
            // Image validation: required, must be an image, specific types, max 2MB
            'id_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. COMPUTE MEMBERSHIP END DATE
        $start = now();
        $months = match ($request->membership_plan) {
            'monthly' => 1,
            '3-months' => 3,
            default => 6, // '6-months' is the default case
        };
        $end = now()->addMonths($months);

        // 3. UPLOAD THE ID PHOTO
        $id_photo_path = null;
        if ($request->hasFile('id_photo')) {
            $file = $request->file('id_photo');

            // This is the recommended Laravel way:
            // The file will be stored in 'storage/app/public/id_photos'
            // and the function returns the relative path (e.g., 'id_photos/unique_hash.jpg').
            $id_photo_path = $file->store('id_photos', 'public');

        }

        // 4. CREATE UNIQUE MEMBER ID
        // // Get last member by descending member_id
$lastMember = Member::orderBy('id', 'desc')->first();
$lastNumber = $lastMember ? intval(substr($lastMember->member_id, 4)) : 0;
$memberID = 'FIT-' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);


        // 5. SAVE TO DATABASE
        Member::create([
            'member_id' => $memberID,
            'full_name' => $request->full_name,
            'facebook_name' => $request->facebook_name,
            'email' => $request->email,
            'membership_plan' => $request->membership_plan,
            // Save the path returned by the store() function
            'id_photo' => $id_photo_path,
            'start_date' => $start,
            'end_date' => $end,
            'status' => 'active',
        ]);


        // Redirect to the success page
        return redirect()->route('member.success')->with('status');
    }

    public function edit($id){
        $member=Member::findOrFail($id);
        return view('edit-member', compact('member'));
    }

    public function update(Request $request, $id)
{
    $member = Member::findOrFail($id);

    // Validation
    $request->validate([
        'full_name' => 'required|string|max:255',
        'facebook_name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:members,email,' . $id,
        'membership_plan' => 'required|in:monthly,3-months,6-months',
        'id_photo' => 'image|mimes:jpeg,png,jpg|max:2048',
    ]);

    // Update membership dates if plan changes
    if ($request->membership_plan !== $member->membership_plan) {
        $months = match ($request->membership_plan) {
            'monthly' => 1,
            '3-months' => 3,
            default => 6,
        };

        $member->start_date = now();
        $member->end_date = now()->addMonths($months);
    }

    // Update ID photo
    if ($request->hasFile('id_photo')) {

        // Delete old photo
        if ($member->id_photo && Storage::disk('public')->exists($member->id_photo)) {
            Storage::disk('public')->delete($member->id_photo);
        }

        // Save new one
        $member->id_photo = $request->file('id_photo')->store('id_photos', 'public');
    }

    // Update main fields
    $member->full_name = $request->full_name;
    $member->facebook_name = $request->facebook_name;
    $member->email = $request->email;
    $member->membership_plan = $request->membership_plan;

    // Save everything
    $member->save();

    return redirect()
        ->route('members.show', $member->id)
        ->with('success', 'Member updated successfully!');
}


    /**
     * Remove the member from storage.
     */
    public function destroy($id)
    {
        $member = Member::findOrFail($id);

        // delete photo from storage
        if ($member->id_photo && Storage::disk('public')->exists($member->id_photo)) {
            Storage::disk('public')->delete($member->id_photo);
        }

        // delete record
        $member->delete();

        return redirect()->route('admin.dashboard')
                         ->with('success', 'Member deleted successfully!');
    }


 

}
