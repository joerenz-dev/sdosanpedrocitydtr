<?php
/**
 * Login Page
 * SDO San Pedro City DTR System
 */
require_once 'config/session.php';
require_once 'config/database.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit();
}

$error = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (!empty($email) && !empty($password)) {
        try {
            $db = getConnection();
            $stmt = $db->prepare("SELECT * FROM users WHERE email = ? AND status = 'active' LIMIT 1");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                // Set session
                setUserSession($user);
                
                // Log activity
                $logStmt = $db->prepare("INSERT INTO activity_logs (user_id, action, description, ip_address) VALUES (?, 'login', 'User logged in', ?)");
                $logStmt->execute([$user['id'], getUserIP()]);
                
                // Redirect to dashboard
                header('Location: dashboard.php');
                exit();
            } else {
                $error = 'Invalid email or password';
            }
        } catch (PDOException $e) {
            $error = 'Database error. Please try again.';
        }
    } else {
        $error = 'Please enter both email and password';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SDO San Pedro City DTR System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0066cc 0%, #0052a3 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-section h1 {
            color: #0066cc;
            font-size: 1.8rem;
            margin-bottom: 5px;
        }

        .logo-section p {
            color: #666;
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #0066cc;
        }

        .error-message {
            background: #fee;
            color: #c33;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: #0066cc;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-login:hover {
            background: #0052a3;
        }

        .links {
            text-align: center;
            margin-top: 20px;
        }

        .links a {
            color: #0066cc;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .links a:hover {
            text-decoration: underline;
        }

        .divider {
            text-align: center;
            margin: 20px 0;
            color: #999;
        }

        .back-home {
            text-align: center;
            margin-top: 15px;
        }

        .back-home a {
            color: #666;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .back-home a:hover {
            color: #0066cc;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 30px 25px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo-section">
            <h1>SDO San Pedro City</h1>
            <p>Daily Time Record System</p>
        </div>

        <?php if (!empty($error)): ?>
        <div class="error-message">
            <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn-login">Login</button>
        </form>

        <div class="links">
            <a href="forgot-password.php">Forgot Password?</a>
        </div>

        <div class="divider">───────</div>

        <div class="back-home">
            <a href="index.php">← Back to Home</a>
        </div>
    </div>
</body>
</html>
