<?php
session_start();

// If config doesn't exist yet, redirect to setup.
if (!file_exists(__DIR__ . '/config.php')) {
    header('Location: setup.php');
    exit;
}

require_once __DIR__ . '/config.php';

// If user is already logged in, redirect to the dashboard.
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}

$error_message = '';
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['password'])) {
        // IMPORTANT: In a real-world scenario, you would authenticate against cPanel.
        // For this simple setup, we are just checking against the password in the config file.
        if (trim($_POST['password']) === CPANEL_PASSWORD) {
            $_SESSION['logged_in'] = true;
            header("Location: dashboard.php");
            exit;
        } else {
            $error_message = "The password you entered is incorrect.";
        }
    } else {
        $error_message = "Please enter your cPanel password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Email Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet" />
    <style>
        :root {
            --text-primary: #F9FAFB;
            --text-secondary: #D1D5DB;
            --border-color: rgba(255, 255, 255, 0.2);
            --error-color: #F87171;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-image: url('https://wallpapercave.com/wp/wp5603784.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: var(--text-primary);
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            padding: 40px 35px;
            text-align: center;

            /* Glassmorphism Effect */
            background: rgba(25, 33, 46, 0.55);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }
        .icon {
            font-size: 4rem;
            color: var(--text-primary);
            margin-bottom: 15px;
            line-height: 1;
        }
        h1 {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .subtitle {
            font-size: 1rem;
            color: var(--text-secondary);
            margin-bottom: 30px;
        }
        .login-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .form-input {
            width: 100%;
            padding: 14px 18px;
            background-color: rgba(0, 0, 0, 0.25);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            font-size: 1rem;
            color: var(--text-primary);
            transition: all 0.2s ease;
        }
        .form-input::placeholder { color: #9CA3AF; }
        .form-input:focus {
            outline: none;
            border-color: #38BDF8;
            box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.3);
        }
        .btn {
            padding: 15px;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            background: linear-gradient(90deg, #8B5CF6, #EC4899);
            color: white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .btn:hover {
            opacity: 0.9;
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }
        .error-message {
            color: var(--error-color);
            background-color: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 8px;
            padding: 12px;
            text-align: center;
            font-weight: 500;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="icon"><i class="ri-shield-keyhole-line"></i></div>
        <h1>Welcome Back</h1>
        <p class="subtitle">Enter your cPanel password to continue.</p>

        <form class="login-form" method="post" action="index.php">
            <input type="password" name="password" class="form-input" placeholder="cPanel Password" required autofocus>
            <button type="submit" class="btn">Unlock Dashboard</button>
        </form>

        <?php if (!empty($error_message)): ?>
            <div class="error-message">
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

