<!DOCTYPE html>
<html>
<head>
    <title>Attendance</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
            background-color: var(--bg);
            margin: 0;
            padding: 20px;
            color: var(--text);
        }

        h1 {
            font-size: 28px;
            margin-bottom: 15px;
            text-align: center;
        }

        /* LOGO */
        .logo-container img {
            height: 110px;
            display: block;
            margin: 0 auto 10px;
        }

        /* SEARCH */
        .search-box {
            max-width: 420px;
            margin: 0 auto 20px;
        }

        .search-box input {
            width: 100%;
            padding: 12px 14px;
            border-radius: 10px;
            border: none;
            font-size: 15px;
            outline: none;
            background: #111;
            color: var(--text);
        }

        /* HAMBURGER */
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

        /* SIDE MENU */
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

        /* LOGOUT BUTTON MATCH DASHBOARD */
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

        /* TABLE */
        .table-wrapper {
            width: 100%;
            overflow-x: auto;
            border-radius: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: var(--card);
            min-width: 1100px;
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

        .status-active {
            background: var(--accent);
            color: #000;
            padding: 4px 10px;
            border-radius: 999px;
            font-weight: bold;
            font-size: 12px;
        }

        .status-expired {
            background: var(--danger);
            color: #fff;
            padding: 4px 10px;
            border-radius: 999px;
            font-weight: bold;
            font-size: 12px;
        }

        .actions {
            display: flex;
            gap: 6px;
        }

        .btn {
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }

        .edit-btn {
            background: #2e70db;
            color: #fff;
        }

        .delete-btn {
            background: var(--danger);
            color: #fff;
        }

    </style>
</head>

<body>

<button class="hamburger" onclick="toggleMenu()">☰</button>

<div class="side-menu" id="sideMenu">
    <a href="{{route('admin.mainDashboard')}}">Dashboard</a>
    <a href="{{ route('admin.profile') }}">Member Profile</a>
    <a href="{{ route('attendance.index') }}">Attendance</a>
    <a href="{{ route('scan.qr') }}">Scan QR Code</a>

    <!-- LOGOUT -->
    <form method="POST" action="{{ route('admin.logout') }}" class="logout">
        @csrf
        <button type="submit">Logout</button>
    </form>
</div>

<div class="logo-container">
    <img src="{{ asset('images/fitcamp-logo.png') }}" alt="FitCamp Logo">
</div>

<h1>Attendance</h1>

<!-- SEARCH -->
<div class="search-box">
    <input type="text" id="searchInput" placeholder="Search member name...">
</div>

<div class="table-wrapper">
<table id="attendanceTable">
    <thead>
        <tr>
            <th>Member ID</th>
            <th>Full Name</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($members as $member)
        @php
       $isExpired = \Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($member->end_date)->endOfDay());
       @endphp


        <tr class="clickable-row"
            data-href="{{ route('attendance.calendar', $member->id) }}">
            <td>{{ $member->member_id }}</td>
            <td class="member-name">{{ $member->full_name }}</td>
            <td>{{ \Carbon\Carbon::parse($member->start_date)->format('M d, Y') }}</td>
            <td>{{ \Carbon\Carbon::parse($member->end_date)->format('M d, Y') }}</td>

            <td>
                @if ($isExpired)
                    <span class="status-expired">EXPIRED</span>
                @else
                    <span class="status-active">ACTIVE</span>
                @endif
            </td>

            <td>
                <div class="actions">
                    <a href="{{ route('members.edit', $member->id) }}" class="btn edit-btn">Edit</a>

                    <form action="{{ route('members.destroy', $member->id) }}"
                          method="POST"
                          onsubmit="return confirm('Delete this member?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn delete-btn">Delete</button>
                    </form>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>

<script>
/* CLICKABLE ROW */
document.querySelectorAll(".clickable-row").forEach(row => {
    row.addEventListener("click", (e) => {
        if (e.target.tagName !== "BUTTON" && e.target.tagName !== "A") {
            window.location = row.dataset.href;
        }
    });
});

/* SEARCH FILTER */
document.getElementById("searchInput").addEventListener("keyup", function () {
    const filter = this.value.toLowerCase();
    const rows = document.querySelectorAll("#attendanceTable tbody tr");

    rows.forEach(row => {
        const name = row.querySelector(".member-name").textContent.toLowerCase();
        row.style.display = name.includes(filter) ? "" : "none";
    });
});

/* MENU TOGGLE */
function toggleMenu() {
    document.getElementById('sideMenu').classList.toggle('active');
}
</script>

</body>
</html>
