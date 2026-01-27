<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Membership Expired</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Animate.css -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        :root {
            --bg: #0f0f0f;
            --card: #1c1c1c;
            --accent: #FFD700;
            --danger: #ff4d4d;
            --text: #ffffff;
            --muted: #b3b3b3;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            height: 100vh;
            background: radial-gradient(circle at top, #1a1a1a, var(--bg));
            color: var(--text);
            font-family: 'Segoe UI', Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .expired-card {
            background: linear-gradient(145deg, #1e1e1e, #141414);
            border-radius: 22px;
            padding: 40px 30px;
            max-width: 380px;
            width: 100%;
            text-align: center;
            box-shadow: 0 20px 50px rgba(0,0,0,.75);
            animation: fadeInUp 0.6s ease;
        }

        .icon {
            width: 72px;
            height: 72px;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: rgba(255,77,77,0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            color: var(--danger);
        }

        h2 {
            margin: 0 0 12px;
            font-size: 24px;
            color: var(--danger);
            letter-spacing: 0.5px;
        }

        p {
            font-size: 15px;
            color: var(--muted);
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .btn {
            display: inline-block;
            width: 100%;
            padding: 14px;
            border-radius: 14px;
            background: var(--accent);
            color: #000;
            font-weight: 700;
            text-decoration: none;
            text-align: center;
            transition: transform .2s ease, box-shadow .2s ease;
            box-shadow: 0 10px 25px rgba(255,215,0,.35);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(255,215,0,.5);
        }

        .hint {
            margin-top: 18px;
            font-size: 13px;
            color: #777;
        }
    </style>
</head>
<body>

<div class="expired-card animate__animated animate__fadeInUp">
    <div class="icon">❌</div>

    <h2>Membership Expired</h2>

    <p>
        Your gym membership is no longer active.<br>
        Please renew your plan to continue accessing the gym.
    </p>

    <a href="{{ route('scan.qr') }}" class="btn">
        Scan Another QR
    </a>

</div>

</body>
</html>
