<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Member</title>

    <style>
        body {
            background: #171717;
            color: #fff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 40px;
        }

        .container {
            background: #222;
            max-width: 600px;
            margin: auto;
            padding: 30px;
            border-radius: 16px;

            border: 1px solid #f3f3f0ff;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #e6c200;
        }

        label {
            font-weight: bold;
            color: #e6c200;
        }

        input, select {
            width: 100%;
            padding: 12px;
            margin: 10px 0 20px;
            border-radius: 10px;
            border: none;
            background: #333;
            color: white;
            font-size: 15px;
        }

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

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: #e6c200;
            color: #000;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
        }

        .btn-submit:hover {
            background: #f5d742;
        }

        .back {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #e6c200;
            text-decoration: none;
            font-weight: bold;
        }

        .back:hover {
            text-decoration: underline;
        }

        .logo-container img {
            height: 90px;
        }
    </style>
</head>

<body>

<div class="container">

  <div class="logo-container">
        <img src="{{ asset('images/fitcamp-logo.png') }}" alt="FitCamp Logo">
    </div>
    <h1>Edit Member</h1>


    <!-- CURRENT PHOTO -->
    <div class="current-photo">
        <img src="{{ asset('storage/' . $member->id_photo) }}" alt="ID Photo">
    </div>

    <form action="{{ route('members.update', $member->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <label>Full Name</label>
    <input type="text" name="full_name" value="{{ $member->full_name }}" required>

    <label>Facebook Name</label>
    <input type="text" name="facebook_name" value="{{ $member->facebook_name }}" required>

    <label>Email</label>
    <input type="email" name="email" value="{{ $member->email }}" required>

    <label>Membership Package</label>
    <select name="membership_package" required>
        <!-- UNLI PASS -->
        <option value="unli_1_month" {{ $member->membership_type == 'unli_1_month' ? 'selected' : '' }}>Unli 1 Month</option>
        <option value="unli_3_months" {{ $member->membership_type == 'unli_3_months' ? 'selected' : '' }}>Unli 3 Months</option>
        <option value="unli_6_months" {{ $member->membership_type == 'unli_6_months' ? 'selected' : '' }}>Unli 6 Months</option>

        <!-- PROFESSIONAL TRAINING -->
        <option value="pt_package_a" {{ $member->membership_type == 'pt_package_a' ? 'selected' : '' }}>PT Package A</option>
        <option value="pt_package_b" {{ $member->membership_type == 'pt_package_b' ? 'selected' : '' }}>PT Package B</option>
        <option value="pt_package_c" {{ $member->membership_type == 'pt_package_c' ? 'selected' : '' }}>PT Package C</option>

        <!-- BOXING/MUAY THAI -->
        <option value="boxing_package_a" {{ $member->membership_type == 'boxing_package_a' ? 'selected' : '' }}>Boxing Package A</option>
        <option value="boxing_package_b" {{ $member->membership_type == 'boxing_package_b' ? 'selected' : '' }}>Boxing Package B</option>
        <option value="boxing_package_c" {{ $member->membership_type == 'boxing_package_c' ? 'selected' : '' }}>Boxing Package C</option>
    </select>

    <!-- Optional: Change ID Photo -->
    <label>Change ID Photo (optional)</label>
    <input type="file" name="id_photo">

    <button class="btn-submit">Save Changes</button>
</form>


    <a class="back" href="{{ route('members.show', $member->id) }}">Back to Profile</a>
</div>

</body>
</html>
