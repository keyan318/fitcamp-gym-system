<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Successfully Registered</title>
<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: 'Arial', sans-serif;
        background-color: #242121ff;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 20px;
    }

    .success-container {
        background-color: #000; /* black container */
        padding: 50px 35px;
        border-radius: 20px;
        width: 100%;
        max-width: 480px;
        text-align: center;
        box-shadow: 0 15px 40px rgba(0,0,0,0.5);
        color: #fff;
    }

    .success-container h1 {
        font-size: 32px;
        color: #ffb600; /* medium yellow */
        margin-bottom: 20px;
        letter-spacing: 1px;
    }

    .success-container p {
        font-size: 18px;
        color: #fff;
        margin-bottom: 30px;
    }

    .success-container .logo-container {
        text-align: center;
        margin-bottom: 25px;
    }

    .success-container .logo-container img {
        max-width: 150px;
        height: auto;
    }

    .success-container a {
        display: inline-block;
        padding: 14px 28px;
        background-color: #ffb600;
        color: #000;
        font-weight: bold;
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .success-container a:hover {
        background-color: #ffcf33;
    }

    @media (max-width: 500px) {
        .success-container {
            padding: 35px 20px;
        }
    }
</style>
</head>
<body>

<div class="success-container">
    <div class="logo-container">
        <img src="{{ asset('images/fitcamp-logo.png') }}" alt="FitCamp Logo">
    </div>

    <h1>Registered Successfully!</h1>
    @if(session('status'))
        <p>{{ session('status') }}</p>
    @endif
    <p>Thank you for joining FitCamp Gym! We’re excited to see you transform.</p>


</div>

</body>
</html>
