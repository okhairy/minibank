<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }} - Login</title>

    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Custom CSS for the login page -->
    <style>
        body {
            background: linear-gradient(135deg, #667eea, #764ba2); /* Elegant gradient */
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Nunito', sans-serif;
        }
        .login-container {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 400px;
            transition: transform 0.3s ease-in-out;
        }
        .login-container:hover {
            transform: scale(1.05); /* Slight zoom effect on hover */
        }
        .login-header {
            font-size: 1.5rem;
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            color: #333;
        }
        .custom-input {
            border-radius: 10px;
            border: 1px solid #d1d5db;
            padding: 10px;
            width: 100%;
            font-size: 1rem;
            margin-bottom: 20px;
            transition: all 0.3s;
        }
        .custom-input:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 5px rgba(102, 126, 234, 0.5);
        }
        .custom-button {
            background-color: #667eea;
            color: white;
            padding: 12px;
            border-radius: 10px;
            text-align: center;
            width: 100%;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .custom-button:hover {
            background-color: #764ba2;
        }
        .forgot-link {
            text-align: right;
            display: block;
            color: #667eea;
            margin-top: 10px;
            font-size: 0.9rem;
        }
        .forgot-link:hover {
            text-decoration: underline;
        }
        .remember-label {
            display: flex;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        {{ $slot }}
    </div>
</body>
</html>
