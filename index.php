<?php
// index.php
// Main entry point for the application - Portal Selection Page

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once __DIR__ . '/include/head.php'; ?>
    <title>Portal Selection</title>
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }

        .title {
            font-size: 36px;
            font-weight: 800;
            text-align: center;
            margin-bottom: 50px;
            color: #333;
        }

        .portal-container {
            display: flex;
            justify-content: center;
            gap: 30px;
            width: 100%;
            max-width: 900px;
            flex-wrap: wrap;
        }

        .portal-card {
            width: 300px;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .portal-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .portal-card.admin:hover {
            border-color: #ec4899;
            box-shadow: 0 15px 30px rgba(236, 72, 153, 0.2);
        }

        .portal-card.user:hover {
            border-color: #3b82f6;
            box-shadow: 0 15px 30px rgba(59, 130, 246, 0.2);
        }

        .portal-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .portal-description {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
        }

        .portal-button {
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }

        .admin .portal-button {
            background-color: #ec4899;
            color: white;
        }

        .user .portal-button {
            background-color: #3b82f6;
            color: white;
        }

        .portal-button:hover {
            opacity: 0.9;
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .portal-container {
                flex-direction: column;
                align-items: center;
            }
            
            .title {
                font-size: 28px;
                margin-bottom: 30px;
            }
        }
    </style>
</head>
<body>
    <!-- Main page title -->
    <h1 class="title">Welcome!</h1>
    
    <!-- Portal selection container -->
    <div class="portal-container">

        <!-- Admin portal card -->
        <div class="portal-card admin" onclick="window.location.href='auth/login.php'">
            <h2 class="portal-title">Admin Portal</h2>
            <p class="portal-description">Access the administration dashboard.</p>
            <div class="portal-button">Go to Admin Portal</div>
        </div>
        
        <!-- User portal card -->
        <div class="portal-card user" onclick="window.location.href='user/user.php'">
            <h2 class="portal-title">User Portal</h2>
            <p class="portal-description">View items as a regular user.</p>
            <div class="portal-button">Go to User Portal</div>
        </div>
    </div>
</body>
</html>