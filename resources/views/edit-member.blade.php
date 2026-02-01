<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<title>Edit Member</title>

<style>
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: Arial, sans-serif;
    background-color: #242121ff;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding: 20px;
}

.form-container {
    background-color: #000;
    padding: 50px 35px;
    border-radius: 20px;
    width: 100%;
    max-width: 520px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.5);
    color: #fff;
}

.logo-container {
    text-align: center;
    margin-bottom: 20px;
}

.logo-container img {
    max-width: 150px;
}

h1 {
    text-align: center;
    margin-bottom: 30px;
    font-size: 30px;
    font-weight: 700;
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    font-size: 14px;
}

input[type="text"],
input[type="email"],
input[type="file"],
input[type="date"] {
    width: 100%;
    padding: 14px 18px;
    margin-bottom: 20px;
    border-radius: 12px;
    border: 1px solid #FFD700;
    background-color: #111;
    color: #fff;
}

input:focus {
    outline: none;
    box-shadow: 0 0 8px rgba(255,215,0,0.6);
}

/* Membership section */
.membership-section {
    margin-bottom: 30px;
}

.membership-title {
    font-size: 18px;
    margin: 25px 0 15px;
    font-weight: bold;
}

.package {
    border: 1px solid #FFD700;
    border-radius: 12px;
    padding: 15px;
    margin-bottom: 12px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 10px;
}

.package input {
    transform: scale(1.2);
}

.package span {
    font-size: 14px;
}

/* Button */
button {
    width: 100%;
    padding: 16px;
    background-color: #FFD700;
    color: #000;
    font-size: 18px;
    font-weight: 700;
    border: none;
    border-radius: 12px;
    cursor: pointer;
}

/* Current photo */
.current-photo {
    text-align: center;
    margin-bottom: 20px;
}

.current-photo img {
    width: 150px;
    height: 150px;
    object-fit: cover;
    border-radius: 12px;
}
</style>
</head>

<body>

<div class="form-container">
    <div class="logo-container">
        <img src="{{ asset('images/fitcamp-logo.png') }}"
             alt="FitCamp Logo"
             class="animate__animated animate__backInDown">
    </div>

    <h1 class="animate__animated animate__zoomIn">Edit Member</h1>
    @if(session('status'))
    <div style="background:#FFD700;color:#000;padding:12px;border-radius:8px;margin-bottom:15px;text-align:center;">
        {{ session('status') }}
    </div>
@endif


    <!-- CURRENT PHOTO -->
    <div class="current-photo">
       @if($member->id_photo)
                @php
                    // Build URL manually to avoid IDE errors
                    $photoUrl = config('filesystems.disks.s3.url') . '/' . $member->id_photo;
                    $defaultPhoto = asset('images/default.png');
                @endphp
                <img
                    src="{{ $photoUrl }}"
                    class="id-photo"
                    alt="{{ $member->full_name }}"
                    onerror="this.onerror=null; this.src='{{ $defaultPhoto }}';">
            @else
                <div class="photo-error">No Photo</div>
            @endif
    </div>

    <form method="POST"
          action="{{ route('members.update', $member->id) }}"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label>Full Name</label>
        <input type="text" name="full_name" value="{{ $member->full_name }}" required>

        <label>Facebook Name</label>
        <input type="text" name="facebook_name" value="{{ $member->facebook_name }}" required>

        <label>Email</label>
        <input type="email" name="email" value="{{ $member->email }}" required>

        <!-- ✅ START & END DATE -->
        <label>Start Date</label>
        <input type="date"
               name="start_date"
               value="{{ \Carbon\Carbon::parse($member->start_date)->format('Y-m-d') }}"
               required>

        <label>End Date</label>
        <input type="date"
               name="end_date"
               value="{{ \Carbon\Carbon::parse($member->end_date)->format('Y-m-d') }}"
               required>

        <!-- MEMBERSHIP SELECTION -->
        <div class="membership-section">
            <div class="membership-title">UNLI PASS</div>

            @foreach ([
                'unli_1_month' => '1 Month – ₱600',
                'unli_3_months' => '3 Months – ₱1,200',
                'unli_6_months' => '6 Months – ₱2,200',
            ] as $value => $label)
            <label class="package">
                <input type="checkbox" name="membership_package[]" value="{{ $value }}"
                       {{ in_array($value, [$member->membership_type, $member->additional_membership]) ? 'checked' : '' }}>
                <span>{{ $label }}</span>
            </label>
            @endforeach

            <div class="membership-title">Exclusive Professional Training</div>

            @foreach ([
                'pt_package_a' => 'Package A – 6 Sessions (₱1,200)',
                'pt_package_b' => 'Package B – 11 + 1 Free (₱2,200)',
                'pt_package_c' => 'Package C – 24 + 5 Free (₱4,800)',
            ] as $value => $label)
            <label class="package">
                <input type="checkbox" name="membership_package[]" value="{{ $value }}"
                       {{ in_array($value, [$member->membership_type, $member->additional_membership]) ? 'checked' : '' }}>
                <span>{{ $label }}</span>
            </label>
            @endforeach

            <div class="membership-title">Boxing / Muay Thai</div>

            @foreach ([
                'boxing_package_a' => 'Package A – 6 Sessions (₱1,500)',
                'boxing_package_b' => 'Package B – 11 + 1 Free (₱2,700)',
                'boxing_package_c' => 'Package C – 24 + 5 Free (₱5,700)',
            ] as $value => $label)
            <label class="package">
                <input type="checkbox" name="membership_package[]" value="{{ $value }}"
                       {{ in_array($value, [$member->membership_type, $member->additional_membership]) ? 'checked' : '' }}>
                <span>{{ $label }}</span>
            </label>
            @endforeach
        </div>

        <label>Change ID Photo (optional)</label>
        <input type="file" name="id_photo" accept="image/*">

        <button type="submit">Save Changes</button>
        <div style="margin-top:10px;">
            <a href="{{ route('attendance.index') }}"
               style="display:inline-block;padding:10px 20px;background:#000;color:#FFD700;border-radius:8px;text-decoration:none;font-weight:bold;">
                Back to Attendance
            </a>
        </div>
    </form>

    @if ($errors->any())
        <div style="background:#ff000033;color:#fff;padding:10px;border-radius:8px;margin-top:15px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>

<script>
const checkboxes = document.querySelectorAll('input[name="membership_package[]"]');
checkboxes.forEach(box => {
    box.addEventListener('change', () => {
        const checked = document.querySelectorAll('input[name="membership_package[]"]:checked');
        if (checked.length > 2) {
            box.checked = false;
            alert('You can only select up to 2 memberships.');
        }
    });
});
</script>

</body>
</html>
