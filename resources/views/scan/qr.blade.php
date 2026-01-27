<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Scan QR Code</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- HTML5 QR Code Library -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <style>
        body {
            background: #0f0f0f;
            color: #fff;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .scanner-box {
            background: #1b1b1b;
            padding: 25px;
            border-radius: 20px;
            text-align: center;
            width: 360px;
            box-shadow: 0 10px 35px rgba(0,0,0,0.6);
            position: relative;
        }

        /* Browser-style back button */
        .back-btn {
            position: absolute;
            top: 15px;
            left: 15px;
            background: transparent;
            border: none;
            color: #ffd700;
            font-size: 20px;
            cursor: pointer;
        }

        h2 {
            margin: 35px 0 20px;
            font-size: 1.4em;
            color: #ffd700;
        }

        #reader {
            width: 280px;
            margin: 0 auto;
            border: 4px dashed #ffd700;
            border-radius: 15px;
        }

        #status {
            margin-top: 20px;
            font-size: 1.1em;
            font-weight: bold;
            color: #00ff00;
            display: none;
            animation: blink 1s infinite;
        }

        @keyframes blink {
            0%, 50%, 100% { opacity: 1; }
            25%, 75% { opacity: 0; }
        }
    </style>
</head>
<body>

<div class="scanner-box">

    <!-- Browser-style back -->
    <button class="back-btn" onclick="goBack()">←</button>

    <!-- Default UI -->
    <div id="idleUI">
        <h2>Scan Member QR</h2>
    </div>

    <!-- QR Camera -->
    <div id="reader"></div>

    <!-- Shown ONLY after QR is scanned -->
    <div id="status">Scanning QR Code...</div>

</div>

<script>
    function goBack() {
        window.location.href = "{{ route('attendance.index') }}";
    }

    const status = document.getElementById("status");
    const idleUI = document.getElementById("idleUI");
    const reader = document.getElementById("reader");

    function onScanSuccess(decodedText) {
        // UI changes ONLY AFTER successful scan
        idleUI.style.display = "none";
        reader.style.display = "none";
        status.style.display = "block";

        html5QrcodeScanner.clear();

        setTimeout(() => {
            window.location.href = "/scan/present/" + decodedText;
        }, 500);
    }

    const html5QrcodeScanner = new Html5QrcodeScanner(
        "reader",
        { fps: 10, qrbox: 220 },
        false
    );

    html5QrcodeScanner.render(onScanSuccess);
</script>

</body>
</html>
