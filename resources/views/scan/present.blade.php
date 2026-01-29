<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Attendance Recorded</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Animate.css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
:root {
    --bg: #1e1e1e;
    --card: #2a2a2a;
    --accent: #FFD700;
    --text: #ffffff;
    --muted: #bdbdbd;
}

* { box-sizing: border-box; }

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: var(--bg);
    color: var(--text);
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

/* ===== CARD ===== */
.card {
    background: var(--card);
    border: 2px;
    border-radius: 20px;
    width: 360px;
    padding: 35px 25px;
    text-align: center;
    box-shadow: 0 8px 20px rgba(255, 215, 0, 0.25);
    animation: fadeScale 0.6s ease-out forwards;
    position: relative;
}

@keyframes fadeScale {
    from { opacity: 0; transform: scale(.9); }
    to { opacity: 1; transform: scale(1); }
}

/* ===== PHOTO ===== */
.photo {
    width: 130px;
    height: 130px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid;
    margin-bottom: 15px;
}

/* ===== MEMBER INFO ===== */
h2 {
    margin: 10px 0 5px;
    font-size: 22px;
    color: var(--text);
}

.member-id {
    color: var(--accent);
    font-weight: bold;
    letter-spacing: 1px;
    margin-bottom: 15px;
}

/* ===== STATUS ===== */
.status {
    margin-top: 15px;
    padding: 14px;
    background: var(--accent);
    border-radius: 12px;
    font-size: 16px;
    font-weight: bold;
    color: black;
}

/* ===== TIMESTAMP ===== */
.timestamp {
    margin-top: 12px;
    font-size: 13px;
    color: var(--muted);
}

/* ===== BUTTON ===== */
a.button {
    display: inline-block;
    margin-top: 20px;
    background: var(--accent);
    color: #000;
    padding: 12px 20px;
    border-radius: 10px;
    font-weight: bold;
    text-decoration: none;
    transition: background 0.3s ease;
}

a.button:hover {
    background: #e6c200;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 400px) {
    .card { width: 90%; padding: 25px 15px; }
    .photo { width: 110px; height: 110px; }
}
</style>
</head>
<body>

<div class="card animate__animated animate__zoomIn">

    <img src="{{ $member->id_photo ? asset('uploads/members/' . $member->id_photo) : asset('images/default.png') }}"
     alt="Member Photo"
     class="photo">

    <!-- Status -->
    <div class="status">
        {{ $member->member_id }} – {{ $member->full_name }} is Present
    </div>

    <!-- Timestamp -->
    <div class="timestamp">
        Recorded on {{ now()->format('M d, Y') }}
    </div>

    <!-- View Attendance Button -->
    <a href="{{ route('attendance.calendar', ['member' => $member->id]) }}" class="button">View Attendance</a>

     <!-- Scan Another Button -->
    <a href="{{ route('scan.qr') }}" class="button">Scan Another QR</a>

</div>

</body>
</html>
