<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - University of Rizal System Binangonan</title>
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
                <h2>
                    @switch($role)
                        @case('faculty')
                            Faculty & Staff Login
                            @break
                        @case('dean')
                            Dean Login
                            @break
                        @case('director')
                            Director Login
                            @break
                        @case('admin')
                            Administrator Login
                            @break
                        @default
                            Account Login
                    @endswitch
                </h2>
            </div>

            <div class="login-body">
                @if ($errors->any())
                    <div class="error-message">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <input type="hidden" name="role" value="{{ $role }}">

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            placeholder="Enter your username"
                            value="{{ old('username') }}"
                            required
                            autofocus
                        >
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="password-toggle">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Enter your password"
                                required
                            >
                            <button type="button" class="toggle-btn" onclick="togglePasswordVisibility()">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="forgot-password">
                        <a href="{{ route('password.request') }}">Forgot password?</a>
                    </div>

                    <button type="submit" class="login-btn">Login</button>
                </form>

                <div class="back-link">
                    <a href="{{ route('login.selection') }}">← Back to Role Selection</a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>