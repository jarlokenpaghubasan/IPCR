<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - University of Rizal System Binangonan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/auth_login.css', 'resources/js/auth_login.js'])
</head>
<body class="gradient-bg">
    <div class="login-container">
        <div class="title">
            <h1>University of Rizal System Binangonan</h1>
            <p>Performance Commitment and Review Module</p>
        </div>

        <div class="login-box">
            <div class="login-header">
                <h2>Reset Password</h2>
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
                    Enter the 6-digit verification code sent to your email and your new password.
                </p>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="Enter your email address"
                            value="{{ $email ?? old('email') }}"
                            required
                            {{ $email ? 'readonly' : '' }}
                        >
                    </div>

                    <div class="form-group">
                        <label for="code">Verification Code</label>
                        <input
                            type="text"
                            id="code"
                            name="code"
                            placeholder="Enter 6-digit code"
                            maxlength="6"
                            pattern="[0-9]{6}"
                            value="{{ old('code') }}"
                            required
                            autofocus
                        >
                    </div>

                    <div class="form-group">
                        <label for="password">New Password</label>
                        <div class="password-toggle">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Enter new password"
                                required
                            >
                            <button type="button" class="toggle-btn" onclick="togglePasswordVisibility()">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirm New Password</label>
                        <div class="password-toggle">
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                placeholder="Confirm new password"
                                required
                            >
                            <button type="button" class="toggle-btn" onclick="toggleConfirmPasswordVisibility()">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="login-btn">Reset Password</button>
                </form>

                <div class="back-link">
                    <a href="{{ route('password.request') }}">← Resend Code</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleConfirmPasswordVisibility() {
            const passwordInput = document.getElementById('password_confirmation');
            const toggleBtn = passwordInput.nextElementSibling;
            const icon = toggleBtn.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
