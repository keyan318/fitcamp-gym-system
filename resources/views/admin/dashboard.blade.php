<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #242121;
            margin: 0;
            padding: 20px;
            color: #fff;
        }

        h1 {
            font-size: 28px;
            margin-bottom: 10px;
            color: #f0efecff;
            text-align: center;
        }

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
            color: #fff;
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
            background: #111;
            min-width: 1000px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            white-space: nowrap;
        }

        th {
            background: #000;
            color: #FFD700;
            font-size: 17px;
        }

        td {
            border-top: 1px solid #333;
            color: #fff;
            font-size: 17px;
        }

        .id-photo {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
            display: block;
            margin: 0 auto;
        }

        .status-active {
            background: #FFD700;
            color: #000;
            padding: 3px 8px;
            border-radius: 999px;
            font-weight: bold;
            font-size: 12px;
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
            color: #FFD700;
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
            max-width: 100%;
        }

        /* 🔥 LOGOUT DESIGN */
        .logout-form {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .logout-btn {
            background: linear-gradient(135deg, #FFD700, #e6c200);
            color: #000;
            border: none;
            padding: 12px 22px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 999px;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 4px 12px rgba(255, 215, 0, 0.25);
        }


        .logout-btn:active {
            transform: scale(0.97);
        }

        @media (max-width: 600px) {
            body { padding: 10px; }
            h1 { font-size: 22px; }
            .top-bar { flex-direction: column; }
            th, td { font-size: 11px; padding: 6px; }
            .id-photo { width: 40px; height: 40px; }
            .stat-number { font-size: 18px; }
        }
    </style>
</head>

<body>

<div class="logo-container">
    <img src="{{ asset('images/fitcamp-logo.png') }}" alt="FitCamp Logo" class="animate__animated animate__bounceInRight">
</div>

<h1 class="animate__animated animate__fadeInDown">Admin Dashboard</h1>

<form method="GET" action="{{ route('admin.dashboard') }}" class="top-bar">
    <input type="text" name="search" class="search-input"
           placeholder="Search by name" value="{{ request('search') }}">

    <select name="filter" class="filter-select" onchange="this.form.submit()">
        <option value="all" {{ request('filter') == 'all' ? 'selected' : '' }}>All Members</option>
        <option value="active" {{ request('filter') == 'active' ? 'selected' : '' }}>Active</option>
        <option value="expired" {{ request('filter') == 'expired' ? 'selected' : '' }}>Expired</option>
    </select>
</form>

<div class="table-wrapper">
<table>
    <tr>
        <th>ID Photo</th>
        <th>ID</th>
        <th>Full Name</th>
        <th>FB Name</th>
        <th>Email</th>
        <th>Membership Type</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Status</th>
    </tr>

    @foreach ($members as $member)
    <tr class="clickable-row" data-href="{{ route('members.show', $member->id) }}">
        <td>
            <img src="{{ $member->id_photo ? asset('storage/'.$member->id_photo) : asset('images/default.png') }}"
                 class="id-photo">
        </td>
        <td>{{ $member->member_id }}</td>
        <td>{{ $member->full_name }}</td>
        <td>{{ $member->facebook_name }}</td>
        <td>{{ $member->email }}</td>
        <td>{{ $membershipLabels[$member->membership_type] ?? $member->membership_type }}</td>
        <td>{{ \Carbon\Carbon::parse($member->start_date)->format('M d, Y') }}</td>
        <td>{{ \Carbon\Carbon::parse($member->end_date)->format('M d, Y') }}</td>
        <td><span class="status-active">{{ $member->status }}</span></td>
    </tr>
    @endforeach
</table>
</div>

<div class="stats-boxes">
    <div class="stat-box">
        <div class="stat-number">{{ $members->count() }}</div>
        <p>Total Members</p>
    </div>

    <div class="stat-box">
        <div class="stat-number">{{ $members->where('status','Active')->count() }}</div>
        <p>Active</p>
    </div>

    <div class="stat-box">
        <div class="stat-number">{{ $members->where('status','Expired')->count() }}</div>
        <p>Expired</p>
    </div>
     <!-- 🔥 LOGOUT -->
    <form method="POST" action="{{ route('admin.logout') }}" class="logout-form">
        @csrf
        <button type="submit" class="logout-btn">Logout</button>
    </form>


</div>

<script>
    document.querySelectorAll(".clickable-row").forEach(row => {
        row.addEventListener("click", () => {
            window.location = row.dataset.href;
        });
    });
</script>

</body>
</html>
