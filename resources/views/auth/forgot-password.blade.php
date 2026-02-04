<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - University of Rizal System Binangonan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/auth_login.css'])
</head>
<body class="gradient-bg">
    <div class="login-container">
        <div class="title">
            <h1>University of Rizal System Binangonan</h1>
            <p>Performance Commitment and Review Module</p>
        </div>

        <div class="login-box">
            <div class="login-header">
                <h2>Forgot Password</h2>
            </div>

            <div class="login-body">
                @if (session('success'))
                    <div class="success-message" style="background-color: #d4edda; color: #155724; padding: 12px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="error-message">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <p style="margin-bottom: 20px; color: #666; font-size: 14px;">
                    Enter your email address and we'll send you a verification code to reset your password.
                </p>

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="Enter your email address"
                            value="{{ old('email') }}"
                            required
                            autofocus
                        >
                    </div>

                    <button type="submit" class="login-btn">Send Verification Code</button>
                </form>

                <div class="back-link">
                    <a href="{{ route('login.selection') }}">← Back to Login</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
