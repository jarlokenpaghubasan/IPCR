<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - University of Rizal System Binangonan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #7e22ce 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .gradient-bg::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: rgba(255, 107, 107, 0.1);
            border-radius: 45% 55% 60% 40% / 55% 45% 40% 60%;
            animation: blob 8s infinite;
        }

        .gradient-bg::after {
            content: '';
            position: absolute;
            bottom: -50%;
            left: -10%;
            width: 500px;
            height: 500px;
            background: rgba(100, 200, 255, 0.1);
            border-radius: 45% 55% 60% 40% / 55% 45% 40% 60%;
            animation: blob 8s infinite reverse;
        }

        @keyframes blob {
            0%, 100% {
                transform: translate(0, 0) scale(1);
            }
            33% {
                transform: translate(30px, -50px) scale(1.1);
            }
            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }
        }

        .role-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            position: relative;
            z-index: 10;
        }

        .role-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        .role-card i {
            font-size: 3rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .role-card:hover i {
            color: #2563eb;
            transform: scale(1.1);
        }

        .role-card h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .role-card p {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .header {
            text-align: center;
            color: white;
            margin-bottom: 3rem;
            position: relative;
            z-index: 10;
        }

        .header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .header p {
            font-size: 1.1rem;
            color: #e5e7eb;
        }

        .container-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 1000px;
            padding: 2rem;
        }
    </style>
</head>
<body class="gradient-bg">
    <div class="container-wrapper">
        <div class="header">
            <h1>University of Rizal System Binangonan</h1>
            <p>Individual/Office Performance Commitment and Review Module</p>
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