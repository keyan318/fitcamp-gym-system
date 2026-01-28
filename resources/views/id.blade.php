<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $member->full_name }} - Digital ID</title>

    <style>
        body {
            margin: 0;
            padding: 20px;
            font-family: Arial, sans-serif;
            background: #242121;
            color: #fff;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-container img {
            height: 90px;
        }

        .back-btn {
            display: inline-block;
            background: #FFD700;
            color: #000;
            padding: 10px 18px;
            font-weight: bold;
            border-radius: 10px;
            text-decoration: none;
            margin-bottom: 20px;
        }

        .back-btn:hover {
            opacity: 0.85;
        }

        /* ================= ID CARD ================= */

        .id-card {
            position: relative;
            width: 350px;
            height: 520px;
            margin: auto;
            background: linear-gradient(135deg, #111 65%, #FFD700 65%);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 15px 30px rgba(0,0,0,0.6);
        }

        /* FitCamp Logo */
        .id-logo {
            position: absolute;
            top: 18px;
            left: 18px;
        }

        .id-logo img {
            height: 90px;
        }

        /* Center Content */
        .id-content {
            position: absolute;
            top: 90px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            width: 100%;
        }

        .id-photo {
            width: 170px;
            height: 180px;
            object-fit: cover;
            border-radius: 12px;
            border: 3px solid #FFD700;
            margin-top:16px;
        }

        .member-name {
            font-size: 20px;
            font-weight: bold;
            margin: 6px 0;
        }

        /* QR Code */
        .id-qr {
            position: absolute;
            bottom: 18px;
            right: 18px;
            background: #fff;
            padding: 6px;
            border-radius: 8px;
        }

        .id-qr canvas {
            width: 90px !important;
            height: 90px !important;
        }

        /* Download Button */
        .download-btn {
            display: block;
            margin: 20px auto;
            padding: 12px 25px;
            background: #FFD700;
            color: #000;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .download-btn:hover {
            opacity: 0.85;
        }
    </style>
</head>

<body>

<div class="logo-container">
    <img src="{{ asset('images/fitcamp-logo.png') }}" alt="FitCamp Logo">
</div>

<a href="{{ route('admin.profile') }}" class="back-btn">⬅</a>

<!-- ================= ID CARD ================= -->

<div class="id-card" id="id-card">

    <!-- Logo -->
    <div class="id-logo">
        <img src="{{ asset('images/fitcamp-logo.png') }}" alt="FitCamp Logo">
    </div>

    <!-- Center -->
    <div class="id-content">
        <img class="id-photo"
             src="{{ $member->id_photo ? Storage::disk('s3')->url($member->id_photo) : asset('images/default.png') }}"
             alt="ID Photo">

        <div class="member-name">{{ $member->full_name }}</div>
    </div>

    <!-- QR -->
    <div class="id-qr">
        <div id="qr-code-container"></div>
    </div>

</div>

<button class="download-btn" onclick="downloadIDCard()">Download ID</button>

<!-- ================= SCRIPTS ================= -->

<script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>

<script>
    // Generate QR Code (Member ID)
    const memberId = "{{ $member->member_id }}";
    const qrContainer = document.getElementById('qr-code-container');

    QRCode.toCanvas(document.createElement('canvas'), memberId, { width: 90 })
        .then(canvas => qrContainer.appendChild(canvas))
        .catch(err => console.error(err));

    // Download ID Card
    function downloadIDCard() {
        const card = document.getElementById('id-card');
        html2canvas(card).then(canvas => {
            const link = document.createElement('a');
            link.download = '{{ $member->full_name }}_FitCamp_ID.png';
            link.href = canvas.toDataURL();
            link.click();
        });
    }
</script>

</body>
</html>
