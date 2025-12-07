<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        font-style: oblique;
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

    /* Table container for mobile scrolling */
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
        min-width: 750px; /* Forces mobile scroll instead of squeezing */
    }

    th,
    td {
        padding: 10px;
        text-align: left;
        white-space: nowrap;
    }

    th {
        background: #000;
        color: #ffb600;
        font-size: 14px;
    }

    td {
        border-top: 1px solid #333;
        color: #fff;
        font-size: 13px;
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
        background: #ffb600;
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
        color: #ffb600;
    }

    .clickable-row {
        cursor: pointer;
        transition: background 0.2s;
    }

    .clickable-row:hover {
        background: #1a1a1a;
    }

    .logo-container img {
        height: 80px;
        max-width: 100%;
    }

    /* --------------------------- */
    /*        MOBILE UI FIXES      */
    /* --------------------------- */

    @media (max-width: 600px) {
        body {
            padding: 10px;
        }

        h1 {
            font-size: 22px;
        }

        .top-bar {
            flex-direction: column;
            width: 100%;
        }

        .search-input,
        .filter-select {
            width: 100%;
            font-size: 14px;
        }

        /* Table tweaks */
        th,
        td {
            font-size: 11px;
            padding: 6px;
        }

        .id-photo {
            width: 40px;
            height: 40px;
        }

        /* Stats boxes */
        .stat-number {
            font-size: 18px;
        }

        .stat-box p {
            font-size: 12px;
        }

        /* Improve touch tap area */
        .clickable-row td {
            padding: 12px 6px;
        }
    }
</style>

</head>
<body>
    <div class="logo-container">
        <img src="{{ asset('images/fitcamp-logo.png') }}" alt="FitCamp Logo">
    </div>

    <h1>Admin Dashboard</h1>

    <form method="GET" action="{{ route('admin.dashboard') }}" class="top-bar">
        <input type="text" name="search" class="search-input" placeholder="Search by name" value="{{ request('search') }}">
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
    </div>

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
