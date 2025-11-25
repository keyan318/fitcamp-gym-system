<!DOCTYPE html>
<html>
<head>
    <title>{{ $member->full_name }} - Profile</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #242121;
            margin: 0;
            padding: 20px;
            color: #fff;
        }

        h1, h2 {
            color: #f0efecff;
            text-align: center;
        }

        .logo-container {
            display: flex;
            justify-content: center;
            margin-bottom: 25px;
        }

        .logo-container img {
            height: 60px;
        }

        .back-btn {
            text-decoration: none;
            color: #000;
            background: #ffb600;
            padding: 10px 18px;
            font-weight: bold;
            border-radius: 10px;
            display: inline-block;
            margin-bottom: 20px;
        }
        .back-btn:hover {
            background: #ffc533;
        }

        .profile-wrapper {
            max-width: 900px;
            margin: auto;
        }

        .card {
            background: #111;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            border: 1px solid #333;
        }

        .title {
            font-size: 22px;
            color: #ffb600;
            margin-bottom: 15px;
        }

        .id-photo {
            width: 240px;
            height: 240px;
            object-fit: cover;
            border-radius: 16px;
            display: block;
            margin: auto;
        }

        .info-list p {
            margin: 12px 0;
            font-size: 18px;
        }
        .info-list span {
            font-weight: bold;
            color: #ffb600;
        }

        .actions {
            text-align: center;
            margin-top: 10px;
        }

        .btn {
            padding: 12px 22px;
            border-radius: 8px;
            font-size: 16px;
            text-decoration: none;
            margin: 8px;
            color: #fff;
        }

        .edit-btn { background: #2e70dbff; }
        .delete-btn { background: #c22d1cff; }

        .btn:hover { opacity: .85; }
    </style>
</head>

<body>
    <!--Fitcamp logo -->
    <div class="logo-container">
        <img src="{{ asset('images/fitcamp-logo.png') }}" alt="FitCamp Logo">
    </div>

    <!--Back to Profile-->
    <a href="{{ route('admin.dashboard') }}" class="back-btn"> Back to Dashboard</a>

    <div class="profile-wrapper">

        <!-- PHOTO -->
        <div class="card">
            <h2 class="title" style="text-align:center;">ID Photo</h2>

            <img
                src="{{ $member->id_photo ? asset('storage/' . $member->id_photo) : asset('images/default.png') }}"
                class="id-photo">
        </div>

        <!-- PERSONAL INFO -->
        <div class="card">
            <h2 class="title">Personal Information</h2>
            <div class="info-list">
                <p><span>Member ID:</span> {{ $member->member_id }}</p>
                <p><span>Full Name:</span> {{ $member->full_name }}</p>
                <p><span>Facebook Name:</span> {{ $member->facebook_name }}</p>
                <p><span>Email:</span> {{ $member->email }}</p>
            </div>
        </div>

        <!-- MEMBERSHIP -->
        <div class="card">
            <h2 class="title">Membership Details</h2>
            <div class="info-list">
                <p><span>Plan:</span> {{ ucfirst($member->membership_plan) }}</p>
                <p><span>Status:</span> {{ $member->status }}</p>
                <p><span>Start Date:</span> {{ $member->start_date }}</p>
                <p><span>End Date:</span> {{ $member->end_date }}</p>
            </div>
        </div>

        <!-- ACTIONS -->
        <div class="card">
            <h2 class="title" style="text-align:center;">Actions</h2>

            <div class="actions">
                <a href="{{ route('members.edit', $member->id) }}" class="btn edit-btn">
                    Edit Member
                </a>

                <form action="{{ route('members.destroy', $member->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn delete-btn" onclick="return confirm('Delete this member?')">
                        Delete Member
                    </button>
                </form>
            </div>
        </div>

    </div>

</body>
</html>
