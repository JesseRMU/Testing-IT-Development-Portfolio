<!-- resources/views/login.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
            background-color: #f7f7f7;
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .input-group {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .error {
            color: red;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        button {
            width: 100%;
            padding: 0.75rem;
            background-color: #444;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
        }

        .bottom-link {
            text-align: center;
            margin-top: 1rem;
        }

        .bottom-link a {
            color: #444;
            text-decoration: none;
            font-weight: bold;
        }

        .bottom-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="card">
    <h2>Login</h2>

    @if (session('error'))
        <div class="error">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('login.submit') }}">
        @csrf

        <div class="input-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <button type="submit">Login</button>
    </form>

    <div class="bottom-link">
        <p>Don't have an account? <a href="{{ route('register') }}">Register</a></p>
    </div>
</div>
</body>
</html>
