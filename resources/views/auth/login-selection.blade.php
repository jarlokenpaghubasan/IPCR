<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - University of Rizal System Binangonan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite('resources/css/auth_login-selection.css')
</head>
<body class="gradient-bg">
    <div class="container-wrapper">
        <div class="header">
            <h1>University of Rizal System Binangonan</h1>
            <p>Performance Commitment and Review Module</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Faculty/Staff Card -->
            <a href="{{ route('login.form', 'faculty') }}" class="role-card group">
                <i class="fas fa-chalkboard-user text-blue-600"></i>
                <h3>Faculty & Staff</h3>
                <p>Login as Faculty or Staff Member</p>
            </a>

            <!-- Dean Card -->
            <a href="{{ route('login.form', 'dean') }}" class="role-card group">
                <i class="fas fa-person-chalkboard text-purple-600"></i>
                <h3>Dean</h3>
                <p>Login as Department Dean</p>
            </a>

            <!-- Director Card -->
            <a href="{{ route('login.form', 'director') }}" class="role-card group">
                <i class="fas fa-user-tie text-green-600"></i>
                <h3>Director</h3>
                <p>Login as Campus Director</p>
            </a>

            <!-- Administrator Card -->
            <a href="{{ route('login.form', 'admin') }}" class="role-card group">
                <i class="fas fa-shield-halved text-red-600"></i>
                <h3>Administrator</h3>
                <p>Login as System Administrator</p>
            </a>
        </div>
    </div>
</body>
</html>