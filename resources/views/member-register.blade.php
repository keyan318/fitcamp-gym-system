<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>FitCamp Membership Registration</title>
<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: 'Arial', sans-serif;
        background-color: #242121ff;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 20px;
    }

    /* Form container in black */
    .form-container {
        background-color: #000; /* black background */
        padding: 50px 35px;
        border-radius: 20px;
        width: 100%;
        max-width: 480px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.5);
        color: #fff;
    }

    /* Heading */
    .form-container h1 {
        text-align: center;
        margin-bottom: 40px;
        font-size: 32px;
        color: #ffb600; /* medium yellow */
        letter-spacing: 1px;
        font-weight: 700;
    }

    /* Labels */
    label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        color: #fffefd; /* yellow labels */
        font-size: 14px;
    }

    /* Inputs */
    input[type="text"],
    input[type="email"],
    input[type="file"],
    select {
        width: 100%;
        padding: 14px 18px;
        margin-bottom: 25px;
        border-radius: 12px;
        border: 1px solid #ffb600; /* yellow border */
        font-size: 15px;
        transition: all 0.3s ease;
        background-color: #111; /* dark input */
        color: #fff;
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    select:focus,
    input[type="file"]:focus {
        border-color: #ffcf33;
        box-shadow: 0 0 8px rgba(255,182,0,0.5);
        outline: none;
    }

    /* Button */
    button {
        width: 100%;
        padding: 16px;
        background-color: #ffb600;
        color: #000;
        font-size: 18px;
        font-weight: 700;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    button:hover {
        background-color: #ffcf33;
    }

    /* Optional note */
    .form-note {
        text-align: center;
        margin-top: 15px;
        font-size: 13px;
        color: #ccc;
    }

    /* Responsive */
    @media (max-width: 500px) {
        .form-container {
            padding: 35px 20px;
        }
    }

    /* Logo styling */
    .logo-container {
        text-align: center;
        margin-bottom: 20px;
    }

    .logo-container img {
        max-width: 150px;
        height: auto;
    }
</style>
</head>
<body>

<div class="form-container">
    <div class="logo-container">
        <img src="{{ asset('images/fitcamp-logo.png') }}" alt="FitCamp Logo">
    </div>

    <h1>FitCamp Membership</h1>

    <form method="POST" action="{{ route('member.store') }}" enctype="multipart/form-data">
        @csrf

        <label for="full_name">Full Name</label>
        <input type="text" id="full_name" name="full_name" value="{{ old('full_name') }}" required>

        <label for="facebook_name">Facebook Name</label>
        <input type="text" id="facebook_name" name="facebook_name" value="{{ old('facebook_name') }}" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required>

        <label for="membership_plan">Membership Plan</label>
        <select id="membership_plan" name="membership_plan" required>
            <option value="monthly">Monthly (₱600)</option>
            <option value="3-months">3 Months (₱2000)</option>
            <option value="6-months">6 Months (₱3500)</option>
        </select>

        <label for="id_photo">ID Photo</label>
        <input type="file" id="id_photo" name="id_photo" accept="image/*" required>

        <button type="submit">Register Now</button>
        <p class="form-note">Join FitCamp today and start your transformation!</p>
    </form>
</div>

</body>
</html>
