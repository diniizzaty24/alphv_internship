<?php
// login.php
// Secure Admin Login System with session-based authentication

// Start session and generate security tokens
session_start();

// CSRF Protection - Generate a unique token for each session
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // 64-character random string
}

// Redirect to admin panel if already logged in
if (isset($_SESSION['loggedin'])) {
    header("Location: ../admin/admin.php");
    exit;
}

// Include database connection and password utilities
include_once __DIR__ . '/../database/database.php';
require_once __DIR__ . '/../auth/password_utils.php';

// Initialize variables
$username = $password = '';
$error = '';

// Process login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verify CSRF token first for security
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = 'Security token mismatch. Please refresh the page.';
        error_log("CSRF token validation failed");
    } else {
        // Sanitize user inputs
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        // Basic validation
        if (empty($username) || empty($password)) {
            $error = 'Please enter both username and password.';
        } else {
            try {
                // Database query using prepared statements to prevent SQL injection
                $stmt = $pdo->prepare("SELECT id, username, password FROM admins WHERE username = :username");
                $stmt->bindParam(':username', $username);
                $stmt->execute();

                // Check if user exists
                if ($stmt->rowCount() == 1) {
                    $user = $stmt->fetch();
                    
                    // Verify password against hashed version
                    if (verifyPassword($password, $user['password'])) {
                        // Login successful - set session variables
                        $_SESSION['loggedin'] = true;
                        $_SESSION['id'] = $user['id'];
                        $_SESSION['username'] = $user['username'];
                        
                        // Regenerate session ID for security
                        session_regenerate_id(true);
                        
                        // Redirect to admin panel
                        header("Location: ../admin/admin.php");
                        exit;
                    } else {
                        $error = 'Invalid username or password.';
                    }
                } else {
                    $error = 'Invalid username or password.';
                }
            } catch (PDOException $e) {
                error_log("Database error: " . $e->getMessage());
                $error = 'System error. Please try again later.';
            }
        }
    }
    
    // Regenerate CSRF token after each POST for security
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once __DIR__ . '/../include/head.php'; ?>
    <title>Admin Login</title>
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .title {
            font-size: 28px;
            font-weight: 800;
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .error-message {
            margin-bottom: 20px;
            padding: 12px;
            background-color: #fee;
            color: #c53030;
            border-radius: 6px;
            border: 1px solid #fecaca;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 6px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 16px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #1C8BC0;
            box-shadow: 0 0 0 3px rgba(28, 139, 192, 0.1);
        }

        .checkbox-group {
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        input[type="checkbox"] {
            width: 16px;
            height: 16px;
        }

        .checkbox-group label {
            margin-bottom: 0;
            font-size: 14px;
            cursor: pointer;
        }

        .login-button {
            width: 100%;
            background-color: #2196F3;
            color: white;
            font-weight: 700;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .login-button:hover {
            background-color: #1976D2;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 30px 20px;
            }
            
            h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <!-- Main login container -->
    <div class="login-container">
        <h1 class="title">Admin Login</h1>
        
        <!-- Display error message if exists -->
        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <!-- Login form -->
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <!-- CSRF Token - Hidden Security Field -->
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <!-- Username input field -->
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required placeholder="Enter your username">
            </div>
            
            <!-- Password input field -->
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password">
            </div>
            
            <!-- Show password checkbox -->
            <div class="checkbox-group">
                <input type="checkbox" id="showPassword" onclick="togglePassword()">
                <label for="showPassword">Show Password</label>
            </div>
            
            <!-- Submit button -->
            <button type="submit" class="login-button">Login</button>
        </form>
    </div>

    <!-- JavaScript to toggle password visibility -->
    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
            } else {
                passwordField.type = 'password';
            }
        }
    </script>
</body>
</html>