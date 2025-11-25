<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #242121;
            margin: 0;
            padding: 20px;
            color: #fff;
        }

        h1 {
            font-size: 32px;
            margin-bottom: 10px;
            color: #f0efecff;
            text-align: center;
            font-style: oblique;
        }

        .top-bar {
            position:sticky;
            top:0;
            z-index:100;
            display: flex;
            gap: 20px;
            align-items: center;
            background: #000;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 25px;
        }

        .search-input, .filter-select {
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #f8f6f0ff;
            background-color: #111;
            color: #fff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #111;
            border-radius: 12px;
            overflow: hidden;
        }

        th {
            background: #000;
            padding: 12px;
            text-align: left;
            color: #ffb600;
        }

        td {
            padding: 12px;
            border-top: 1px solid #333;
            color: #fff;
        }

        .id-photo {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 50%;
           display: block;
            margin: 0 auto;
        }

        .status-active {
            background: #ffb600;
            color: #000;
            padding: 5px 12px;
            border-radius: 999px;
            font-weight: bold;
            font-size: 14px;
        }

        .stats-boxes {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 25px;
        }

        .stat-box {
            background: #000;
            padding: 20px;
            text-align: center;
            border-radius: 12px;
        }

        .stat-number {
            font-size: 32px;
            font-weight: bold;
            color: #ffb600;
        }

        /* CLICKABLE ROW */
        .clickable-row {
            cursor: pointer;
            transition: background 0.2s;
        }
        .clickable-row:hover {
            background: #1a1a1a;
        }

        .logo-container img {
            height: 100px;
        }
    </style>
</head>
<body>


    <div class="form-container">

     <!-- Fitcamp Logo -->
    <div class="logo-container">
        <img src="{{ asset('images/fitcamp-logo.png') }}" alt="FitCamp Logo">
    </div>

    <h1>Admin Dashboard</h1>

    <!-- Top Action Bar -->

     <form method="GET" action="{{ route('admin.dashboard') }}" class="top-bar">

    <input
        type="text"
        name="search"
        class="search-input"
        placeholder="Search by name"
        value="{{ request('search') }}"
    >

    <select name="filter" class="filter-select" onchange="this.form.submit()">
        <option value="all" {{ request('filter') == 'all' ? 'selected' : '' }}>All Members</option>
        <option value="active" {{ request('filter') == 'active' ? 'selected' : '' }}>Active</option>
        <option value="expired" {{ request('filter') == 'expired' ? 'selected' : '' }}>Expired</option>
    </select>

</form>



    <!-- Members Table -->
    <table>
        <tr>
            <th>ID Photo</th>
            <th>ID</th>
            <th>Full Name</th>
            <th>FB Name</th>
            <th>Email</th>
            <th>Membership Plan</th>
            <th>Status</th>
        </tr>

        @foreach ($members as $member)
            <tr class="clickable-row" data-href="{{ route('members.show', $member->id) }}">
                <td>
                    @if($member->id_photo)
                        <img src="{{ asset('storage/' . $member->id_photo) }}" class="id-photo" alt="ID photo">
                    @else
                        <img src="{{ asset('images/default.png') }}" class="id-photo" alt="No Photo">
                    @endif
                </td>
                <td>{{ $member->member_id }}</td>
                <td>{{ $member->full_name }}</td>
                <td>{{ $member->facebook_name }}</td>
                <td>{{ $member->email }}</td>
                <td>{{ ucfirst($member->membership_plan) }}</td>
                <td><span class="status-active">{{ $member->status }}</span></td>
            </tr>
        @endforeach
    </table>

    <!-- Stats -->
    <div class="stats-boxes">
        <div class="stat-box">
            <div class="stat-number">{{ $members->count() }}</div>
            <p>Total Members</p>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $members->where('status', 'active')->count() }}</div>
            <p>Active</p>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $members->where('status', 'expired')->count() }}</div>
            <p>Expired</p>
        </div>
    </div>

    <!-- JS to make row clickable -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const rows = document.querySelectorAll(".clickable-row");
            rows.forEach(row => {
                row.addEventListener("click", () => {
                    window.location = row.dataset.href;
                });
            });
        });
    </script>
</body>
</html>
