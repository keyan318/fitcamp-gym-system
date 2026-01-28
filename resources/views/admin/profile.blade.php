<!-- Member Profile Blade Updated -->
<!DOCTYPE html>
<html>
<head>
    <title>Member Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        :root {
            --bg: #242121;
            --card: #111;
            --accent: #FFD700;
            --text: #fff;
            --muted: #bdbdbd;
            --danger: #ff4d4d;
        }

        * { box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: var(--bg);
            color: var(--text);
        }

        h1 {
            font-size: 28px;
            margin-bottom: 10px;
            text-align: center;
        }

        /* ===== TOP BAR ===== */
        .top-bar {
            position: sticky;
            top: 0;
            z-index: 100;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
            background: #000;
            padding: 10px;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .search-input,
        .filter-select {
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #f8f6f0ff;
            background-color: #111;
            color: var(--text);
            flex: 1 1 150px;
            font-size: 14px;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: var(--card);
            min-width: 1200px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            white-space: nowrap;
        }

        th {
            background: #000;
            color: var(--accent);
            font-size: 16px;
        }

        td {
            border-top: 1px solid #333;
            font-size: 15px;
        }

        .id-photo {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
            display: block;
            margin: 0 auto;
        }

        .clickable-row {
            cursor: pointer;
            transition: background 0.2s;
        }

        .clickable-row:hover {
            background: #1a1a1a;
        }

        .logo-container img {
            height: 130px;
            display: block;
            margin: auto;
        }

        .stats-boxes {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .stat-box {
            background: #000;
            padding: 15px;
            text-align: center;
            border-radius: 12px;
        }

        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: var(--accent);
        }

        /* ===== LOGOUT MATCH DASHBOARD STYLE ===== */
        .logout {
            margin-top: auto;
        }

        .logout button {
            width: 100%;
            background: transparent;
            border: 2px solid var(--danger);
            color: var(--danger);
            padding: 10px;
            border-radius: 10px;
            font-weight: bold;
            cursor: pointer;
        }

        .logout button:hover {
            background: var(--danger);
            color: #fff;
        }

        /* ===== HAMBURGER & SIDE MENU ===== */
        .hamburger {
            position: fixed;
            top: 15px;
            left: 15px;
            background: #000;
            color: var(--accent);
            border: none;
            font-size: 26px;
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            z-index: 1001;
        }

        .side-menu {
            position: fixed;
            top: 0;
            left: -260px;
            width: 240px;
            height: 100%;
            background: #000;
            padding: 70px 20px;
            transition: left 0.3s ease;
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }

        .side-menu.active {
            left: 0;
        }

        .side-menu a {
            display: block;
            color: var(--accent);
            text-decoration: none;
            font-weight: bold;
            margin-bottom: 15px;
        }


    </style>
</head>

<body>

<button class="hamburger" onclick="toggleMenu()">☰</button>

<div class="side-menu" id="sideMenu">
    <a href="{{route('admin.mainDashboard')}}">Dashboard</a>
    <a href="{{ route('admin.profile') }}">Member profile</a>
    <a href="{{ route('attendance.index') }}">Attendance</a>
    <a href="{{ route('scan.qr') }}">Scan QR Code</a>

    <!-- LOGOUT -->
    <form method="POST" action="{{ route('admin.logout') }}" class="logout">
        @csrf
        <button type="submit">Logout</button>
    </form>
</div>

<div class="logo-container">
    <img src="{{ asset('images/fitcamp-logo.png') }}">
</div>

<h1>Member profile</h1>

<div class="table-wrapper">
<table>
    <tr>
        <th>ID Photo</th>
        <th>Member ID</th>
        <th>Full Name</th>
        <th>FB Name</th>
        <th>Email</th>
        <th>Membership</th>
        <th>Additional</th>
    </tr>

    @foreach ($members->sortBy('member_id') as $member)
    <tr class="clickable-row" data-href="{{ route('members.show', $member->id) }}">
        <td>
            <img src="{{ $member->id_photo
                ? asset('storage/'.$member->id_photo)
                : asset('images/default.png') }}"
                 class="id-photo">
        </td>
        <td>{{ $member->member_id }}</td>
        <td>{{ $member->full_name }}</td>
        <td>{{ $member->facebook_name }}</td>
        <td>{{ $member->email }}</td>
        <td>{{ $membershipLabels[$member->membership_type] ?? $member->membership_type }}</td>
        <td>{{ $member->additional_membership
                ? ($membershipLabels[$member->additional_membership] ?? $member->additional_membership)
                : '—' }}</td>
    </tr>
    @endforeach
</table>
</div>

<script>
document.querySelectorAll(".clickable-row").forEach(row => {
    row.addEventListener("click", () => {
        window.location = row.dataset.href;
    });
});

function toggleMenu() {
    document.getElementById('sideMenu').classList.toggle('active');
}
</script>

</body>
</html>
