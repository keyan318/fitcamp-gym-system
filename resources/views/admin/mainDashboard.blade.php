<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gym Dashboard</title>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
:root{
    --bg:#1e1e1e;
    --card:#2a2a2a;
    --accent:#FFD700;
    --text:#ffffff;
    --muted:#bdbdbd;
    --danger:#ff4d4d;
}

*{ box-sizing:border-box; }

body{
    margin:0;
    font-family:Arial, sans-serif;
    background:var(--bg);
    color:var(--text);
}

/* ===== ANIMATIONS ===== */
@keyframes fadeScale{
    from{ opacity:0; transform:scale(.9); }
    to{ opacity:1; transform:scale(1); }
}

@keyframes slideUp{
    from{ opacity:0; transform:translateY(15px); }
    to{ opacity:1; transform:translateY(0); }
}

/* ===== HEADER ===== */
.top-header{
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    padding:28px 16px 20px;
}

.logo-container img{
    height:110px;
    animation:fadeScale .7s ease-out forwards;
}

.header-title{
    margin-top:10px;
    font-size:24px;
    font-weight:bold;
    animation:slideUp .7s ease-out forwards;
}

/* ===== HAMBURGER ===== */
.hamburger{
    position:fixed;
    top:15px;
    left:15px;
    background:#000;
    color:var(--accent);
    border:none;
    font-size:24px;
    padding:8px 12px;
    border-radius:10px;
    cursor:pointer;
    z-index:1001;
}

/* ===== SIDE MENU ===== */
.side-menu{
    position:fixed;
    top:0;
    left:-260px;
    width:240px;
    height:100%;
    background:#000;
    padding:70px 20px 20px;
    transition:0.3s;
    z-index:1000;
    display:flex;
    flex-direction:column;
}

.side-menu.active{ left:0; }

.side-menu a{
    color:var(--accent);
    text-decoration:none;
    font-weight:bold;
    margin-bottom:18px;
}

/* ===== LOGOUT ===== */
.logout{
    margin-top:auto;
}

.logout button{
    width:100%;
    background:transparent;
    border:2px solid var(--danger);
    color:var(--danger);
    padding:10px;
    border-radius:10px;
    font-weight:bold;
    cursor:pointer;
}

.logout button:hover{
    background:var(--danger);
    color:#fff;
}

/* ===== MAIN ===== */
.container{
    padding:20px;
    max-width:1200px;
    margin:auto;
}

/* ===== CARDS ===== */
.cards{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:16px;
    margin-bottom:35px;
}

.card{
    background:var(--card);
    border-radius:16px;
    padding:20px;
    box-shadow:0 8px 20px rgba(0,0,0,.3);
}

.card span{
    font-size:14px;
    color:var(--muted);
}

.card h2{
    margin-top:8px;
    font-size:34px;
    color:var(--accent);
}

/* ===== CHART ===== */
.panel{
    background:var(--card);
    border-radius:16px;
    padding:20px;
    box-shadow:0 8px 20px rgba(0,0,0,.3);
    height:350px;
}

.panel h3{
    margin-bottom:16px;
    font-size:18px;
}

.panel canvas{
    width:100% !important;
    height:100% !important;
}

/* ===== MOBILE ===== */
@media(max-width:768px){
    .logo-container img{ height:90px; }
    .header-title{ font-size:20px; }
    .container{ padding:14px; }
    .cards{ gap:12px; margin-bottom:20px; }
    .card{ padding:14px; }
    .card h2{ font-size:24px; }
    .panel{ height:300px; }
}
</style>
</head>
<body>

<button class="hamburger" onclick="toggleMenu()">☰</button>

<!-- SIDE MENU -->
<div class="side-menu" id="sideMenu">
    <a href="{{ route('admin.mainDashboard') }}">Dashboard</a>
    <a href="{{ route('admin.profile') }}">Member Profile</a>
    <a href="{{ route('attendance.index') }}">Attendance</a>
    <a href="{{ route('scan.qr') }}">Scan QR Code</a>

    <form method="POST" action="{{ route('admin.logout') }}" class="logout">
        @csrf
        <button type="submit">Logout</button>
    </form>
</div>

<!-- HEADER -->
<div class="top-header">
    <div class="logo-container">
        <img src="{{ asset('images/fitcamp-logo.png') }}">
    </div>
    <div class="header-title">DASHBOARD</div>
</div>

<div class="container">

    <div class="cards">
        <div class="card">
            <span>Total Members</span>
            <h2>{{ $totalMembers }}</h2>
        </div>
        <div class="card">
            <span>Attendances Today</span>
            <h2>{{ $attendancesToday }}</h2>
        </div>
    </div>

    <div class="cards">
        <div class="card">
            <span>Active Members</span>
            <h2>{{ $activeMembers }}</h2>
        </div>
        <div class="card">
            <span>Expired Members</span>
            <h2>{{ $expiredMembers }}</h2>
        </div>
    </div>

    <!-- 👇 DATA STORED SAFELY HERE -->
    <div class="panel"
         data-dates='@json($attendanceGraph["dates"])'
         data-counts='@json($attendanceGraph["counts"])'>
        <h3>Attendance (Last 7 Days)</h3>
        <canvas id="attendanceChart"></canvas>
    </div>

</div>

<script>
function toggleMenu(){
    document.getElementById('sideMenu').classList.toggle('active');
}

// ✅ PURE JAVASCRIPT — NO BLADE HERE
const panel = document.querySelector('.panel');
const dates = JSON.parse(panel.dataset.dates);
const counts = JSON.parse(panel.dataset.counts);

const ctx = document.getElementById('attendanceChart');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: dates,
        datasets: [{
            data: counts,
            backgroundColor: 'rgba(255,215,0,0.85)',
            borderRadius: 8,
            maxBarThickness: 60
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: {
                ticks: { color: '#ccc' },
                grid: { display: false }
            },
            y: {
                beginAtZero: true,
                ticks: { color: '#ccc' },
                grid: { color: 'rgba(255,255,255,0.05)' }
            }
        }
    }
});
</script>

</body>
</html>
