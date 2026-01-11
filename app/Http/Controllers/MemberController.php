<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member; // Make sure this model exists
use Illuminate\Support\Str; // Required if you use Str::slug for filenames
use Illuminate\Support\Facades\Storage;
use function Ramsey\Uuid\v1;
use Carbon\Carbon;

class MemberController extends Controller
{
    /**
     * Admin Dashboard: Display a listing of the members.
     * Fetches all members and passes them to the 'admin' view.
     */
   public function index(Request $request)
{
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

    // Mapping for friendly membership names
    $membershipLabels = $this->membershipLabels();

    // ✅ Only one return statement
    return view('dashboard', compact('members', 'membershipLabels'));
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
    $request->validate([
        'full_name' => 'required|string',
        'facebook_name' => 'required|string',
        'email' => 'required|email|unique:members, email',
        'membership_package' => 'required|string', // radio button
        'id_photo' => 'required|image',
    ]);

    // UPLOAD ID PHOTO
    $id_photo_path = $request->file('id_photo')->store('id_photos', 'public');

    // GENERATE MEMBER ID
    $lastMember = Member::orderBy('id', 'desc')->first();
    $lastNumber = $lastMember ? intval(substr($lastMember->member_id, 4)) : 0;
    $memberID = 'FIT-' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

   //Membership Rules

   $packages=[

   //UNLI PASS
    'unli_1_month'=> 30,
    'unli_3_months'=> 90,
    'unli_6_months'=> 180,

    //PROFESSIONAL TRAINING
    'pt_package_a'=> 20,
    'pt_package_b'=> 60,
    'pt_package_c'=> 150,

    //BOXING/MUAY THAI
      'boxing_package_a'=> 20,
      'boxing_package_b'=> 60,
      'boxing_package_c'=> 150,

   ];

     $validDays=$packages[$request->membership_package];

     $startDate= Carbon::today();
     $endDate=$startDate->copy()->addDays($validDays);


    // SAVE TO DATABASE
    Member::create([
        'member_id' => $memberID,
        'full_name' => $request->full_name,
        'facebook_name' => $request->facebook_name,
        'email' => $request->email,
        'membership_type' => $request->membership_package, // ✅ just save the radio value
        'valid_days' =>$validDays,
         'start_date'=>$startDate,
         'end_date' =>$endDate,
        'id_photo' => $id_photo_path,
        'status' => 'Active',
    ]);

    return redirect()->route('member.success')->with('status', 'You have successfully registered!');
}

private function membershipLabels()
{
    return [
        // UNLI PASS
        'unli_1_month'   => '1 Month – ₱600',
        'unli_3_months'  => '3 Months – ₱1,200',
        'unli_6_months'  => '6 Months – ₱2,200',

        // PROFESSIONAL TRAINING
        'pt_package_a'   => 'Package A – 6 Sessions (₱1,200)',
        'pt_package_b'   => 'Package B – 11 + 1 Free (₱2,200)',
        'pt_package_c'   => 'Package C – 24 + 5 Free (₱4,800)',

        // BOXING / MUAY THAI
        'boxing_package_a' => 'Package A – 6 Sessions (₱1,500)',
        'boxing_package_b' => 'Package B – 11 + 1 Free (₱2,700)',
        'boxing_package_c' => 'Package C – 24 + 5 Free (₱5,700)',
    ];
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
        'membership_package' => 'required|string', // matches store()
        'id_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    // Membership packages (same as store)
    $packages = [
        // UNLI PASS
        'unli_1_month' => 30,
        'unli_3_months' => 90,
        'unli_6_months' => 180,

        // PROFESSIONAL TRAINING
        'pt_package_a' => 20,
        'pt_package_b' => 60,
        'pt_package_c' => 150,

        // BOXING/MUAY THAI
        'boxing_package_a' => 20,
        'boxing_package_b' => 60,
        'boxing_package_c' => 150,
    ];

    // Recalculate membership dates if package changes
    if ($request->membership_package !== $member->membership_type) {
        $validDays = $packages[$request->membership_package] ?? 30; // fallback 30 days
        $member->start_date = Carbon::today();
        $member->end_date = Carbon::today()->addDays($validDays);
        $member->valid_days = $validDays;
        $member->membership_type = $request->membership_package;
    }

    // Update ID photo if new file uploaded
    if ($request->hasFile('id_photo')) {
        if ($member->id_photo && Storage::disk('public')->exists($member->id_photo)) {
            Storage::disk('public')->delete($member->id_photo);
        }
        $member->id_photo = $request->file('id_photo')->store('id_photos', 'public');
    }

    // Update main fields
    $member->full_name = $request->full_name;
    $member->facebook_name = $request->facebook_name;
    $member->email = $request->email;

    // Save changes
    $member->save();

    return redirect()
        ->route('members.show', $member->id)
        ->with('status', 'Member updated successfully!');
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
