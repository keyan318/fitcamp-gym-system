<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
  />
    <style>
        body {
            font-family: Arial;
            background: #242121;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-box {
            background: #000;
            padding: 32px 28px;
            border-radius: 12px;
            width: 340px;
            box-sizing: border-box;
        }

        .logo-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .logo-container img {
            max-width: 140px;
            width: 100%;
            height: auto;
        }

        h2 {
            text-align: center;
            color:#FFD700;
            margin: 10px 0 22px;
        }

        input {
            width: 100%;
            padding: 11px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #fff;
            background: #111;
            color: #fff;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 11px;
            margin-top: 14px;
            border-radius: 8px;
            border: none;
            background: #FFD700;
            color: #000;
            font-weight: bold;
            cursor: pointer;
        }

        .error {
            color: red;
            font-size: 13px;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <div class="login-box">
        <div class="logo-container">
            <img src="{{ asset('images/fitcamp-logo.png') }}" alt="FitCamp Logo" class="animate__animated animate__backInDown" >
        </div>

        <h2 class="animate__animated animate__backInDown">Admin Login</h2>

        @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
    @csrf
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>

    </div>

</body>
</html>
