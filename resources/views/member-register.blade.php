<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<title>FitCamp Membership Registration</title>

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
input[type="file"] {
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

.form-note {
    text-align: center;
    margin-top: 15px;
    font-size: 13px;
    color: #ccc;
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

    <h1 class="animate__animated animate__zoomIn">Gym Membership</h1>

    <form method="POST"
          action="{{ route('member.store') }}"
          enctype="multipart/form-data">
        @csrf

        <label>Full Name</label>
        <input type="text" name="full_name" required>

        <label>Facebook Name</label>
        <input type="text" name="facebook_name" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <!-- MEMBERSHIP SELECTION -->
        <div class="membership-section">



            <div class="membership-title">UNLI PASS</div>
            <label class="package">
                <input type="checkbox" name="membership_package[]" value="unli_1_month">
                <span>1 Month – ₱600</span>
            </label>
            <label class="package">
                <input type="checkbox" name="membership_package[]" value="unli_3_months">
                <span>3 Months – ₱1,200</span>
            </label>
            <label class="package">
                <input type="checkbox" name="membership_package[]" value="unli_6_months">
                <span>6 Months – ₱2,200</span>
            </label>

            <div class="membership-title">Exclusive Professional Training</div>
            <label class="package">
                <input type="checkbox" name="membership_package[]" value="pt_package_a">
                <span>Package A – 6 Sessions (₱1,200)</span>
            </label>
            <label class="package">
                <input type="checkbox" name="membership_package[]" value="pt_package_b">
                <span>Package B – 11 + 1 Free (₱2,200)</span>
            </label>
            <label class="package">
                <input type="checkbox" name="membership_package[]" value="pt_package_c">
                <span>Package C – 24 + 5 Free (₱4,800)</span>
            </label>

            <div class="membership-title">Boxing / Muay Thai</div>
            <label class="package">
                <input type="checkbox" name="membership_package[]" value="boxing_package_a">
                <span>Package A – 6 Sessions (₱1,500)</span>
            </label>
            <label class="package">
                <input type="checkbox" name="membership_package[]" value="boxing_package_b">
                <span>Package B – 11 + 1 Free (₱2,700)</span>
            </label>
            <label class="package">
                <input type="checkbox" name="membership_package[]" value="boxing_package_c">
                <span>Package C – 24 + 5 Free (₱5,700)</span>
            </label>

        </div>

        <label>ID Photo</label>
        <input type="file" name="id_photo" accept="image/*" required>

        <button type="submit">Register Now</button>
        <p class="form-note">Join FitCamp today and start your transformation!</p>
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

<!-- LIMIT TO 2 MEMBERSHIPS -->
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
