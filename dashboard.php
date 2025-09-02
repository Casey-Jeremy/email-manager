<?php
session_start();

// If config doesn't exist, redirect to setup.
if (!file_exists(__DIR__ . '/config.php')) {
    header('Location: setup.php');
    exit;
}
require_once __DIR__ . '/config.php';

// If the user is not logged in, redirect them to the login page.
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device--width, initial-scale=1.0">
    <title>Email Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet" />
    <style>
        :root {
            --bg-dark: <?= defined('BG_COLOR') ? BG_COLOR : '#111827' ?>;
            --bg-card: #1F2937; --border-color: #374151;
            --text-primary: #F9FAFB; --text-secondary: #9CA3AF; --accent-blue: #38BDF8;
            --accent-blue-dark: #0EA5E9;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: var(--bg-dark); color: var(--text-primary); line-height: 1.6; min-height: 100vh; }
        .container { max-width: 1400px; margin: 0 auto; padding: 25px; }
        .header { display: flex; justify-content: space-between; align-items: center; padding: 20px 0; margin-bottom: 30px; border-bottom: 1px solid var(--border-color); }
        .logo { display: flex; align-items: center; gap: 16px; }
        .logo-icon { width: 50px; height: 50px; border-radius: 10px; padding: 4px; }
        .logo-icon img { width: 100%; height: 100%; object-fit: contain; background: white; border-radius: 8px; padding: 4px; }
        .header h1 { font-size: 1.75rem; font-weight: 600; }
        .header-actions { display: flex; align-items: center; gap: 20px; }
        .user-badge { color: var(--text-secondary); display: flex; align-items: center; gap: 8px; }
        .btn-logout { background-color: var(--border-color); color: var(--text-primary); padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 8px; transition: background-color 0.2s ease; }
        .btn-logout:hover { background-color: #4B5563; }
        .stats-bar { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; margin-bottom: 30px; }
        .stat-card { background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; padding: 25px; display: flex; align-items: center; gap: 20px; }
        .stat-icon { font-size: 2.5rem; color: var(--accent-blue); }
        .stat-info .stat-number { font-size: 2rem; font-weight: 700; color: var(--text-primary); line-height: 1.2; }
        .stat-info .stat-label { font-size: 0.9rem; color: var(--text-secondary); }
        .card { background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; margin-bottom: 30px; overflow: hidden; }
        .card-header { padding: 20px 25px; border-bottom: 1px solid var(--border-color); }
        .card-header h2 { font-size: 1.25rem; font-weight: 600; display: flex; align-items: center; gap: 10px; }
        .card-body { padding: 25px; }
        .dashboard-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; margin-bottom: 8px; font-weight: 500; color: var(--text-secondary); font-size: 0.9rem; }
        .dashboard-form-input { width: 100%; padding: 12px 15px; background-color: var(--bg-dark); border: 1px solid var(--border-color); border-radius: 8px; font-size: 1rem; color: var(--text-primary); transition: all 0.2s ease; box-sizing: border-box; }
        .dashboard-form-input:focus { outline: none; border-color: var(--accent-blue); box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.3); }
        .password-input-container { position: relative; }
        .password-toggle { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; font-size: 1.2rem; color: var(--text-secondary); }
        .input-group { display: flex; }
        .input-group input { border-top-right-radius: 0; border-bottom-right-radius: 0; }
        .input-suffix { background-color: var(--border-color); padding: 12px 15px; font-weight: 500; font-size: 0.95rem; border-top-right-radius: 8px; border-bottom-right-radius: 8px; color: var(--text-secondary); }
        .password-strength-meter { margin-top: 10px; }
        .strength-bars { display: flex; gap: 5px; height: 6px; border-radius: 3px; overflow: hidden; }
        .strength-bar { flex: 1; background-color: var(--border-color); transition: background-color 0.3s ease; }
        .strength-text { margin-top: 5px; font-size: 0.8rem; color: var(--text-secondary); font-weight: 500; }
        .strength-bar.weak { background-color: #ef4444; }
        .strength-bar.medium { background-color: #f97316; }
        .strength-bar.strong { background-color: #22c55e; }
        .btn { display: flex; width: 100%; align-items: center; justify-content: center; gap: 10px; padding: 14px; border: none; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.2s ease; }
        .btn-primary { background-color: var(--accent-blue); color: var(--bg-dark); }
        .btn-primary:hover:not(:disabled) { background-color: var(--accent-blue-dark); }
        .btn-primary:disabled { background-color: var(--border-color); color: var(--text-secondary); cursor: wait; }
        .email-list { min-height: 200px; max-height: 485px; overflow-y: auto; padding-right: 10px; }
        .email-item { display: flex; align-items: center; padding: 15px; border-bottom: 1px solid var(--border-color); transition: background-color 0.2s ease; }
        .email-item:hover { background-color: rgba(255, 255, 255, 0.03); }
        .email-item:last-child { border-bottom: none; }
        .email-info h4 { font-weight: 500; font-size: 1rem; color: var(--text-primary); word-break: break-all; }
        .empty-state, .loading-state { text-align: center; padding: 40px; color: var(--text-secondary); }
        .empty-state-icon, .loading-state i { font-size: 3rem; margin-bottom: 15px; color: var(--border-color); }
        .loading-state i { animation: spin 1.5s linear infinite; }
        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        .toast-container { position: fixed; bottom: 20px; right: 20px; z-index: 1000; }
        .toast { padding: 15px 20px; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); display: flex; align-items: center; gap: 10px; color: var(--text-primary); animation: slideInUp 0.3s ease forwards; min-width: 320px; }
        .toast.closing { animation: slideOutDown 0.3s ease forwards; }
        .toast-success { background-color: #22C55E; }
        .toast-error { background-color: #EF4444; }
        .toast i { font-size: 1.5rem; }
        @keyframes slideInUp { from { opacity: 0; transform: translateY(100%); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slideOutDown { from { opacity: 1; transform: translateY(0); } to { opacity: 0; transform: translateY(100%); } }
        @media (max-width: 1024px) { .dashboard-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body style="background-color: <?= BG_COLOR ?>;">
    <div id="toast-container" class="toast-container"></div>
    <div class="container">
        <header class="header">
            <div class="logo">
                <div class="logo-icon">
                    <img src="<?= htmlspecialchars(LOGO_PATH) ?>" alt="Logo">
                </div>
                <h1>Email Dashboard</h1>
            </div>
            <div class="header-actions">
                <div class="user-badge"><i class="ri-user-line"></i> <?= htmlspecialchars(CPANEL_USER) ?></div>
                <a href="logout.php" class="btn-logout"><i class="ri-logout-box-r-line"></i> Logout</a>
            </div>
        </header>

        <div class="stats-bar">
            <div class="stat-card">
                <i class="stat-icon ri-mail-line"></i>
                <div class="stat-info">
                    <div class="stat-number" id="total-accounts-stat">...</div>
                    <div class="stat-label">Total Email Accounts</div>
                </div>
            </div>
            <div class="stat-card">
                <i class="stat-icon ri-server-line"></i>
                <div class="stat-info">
                    <div class="stat-number" style="font-size: 1.5rem; word-break: break-all;"><?= htmlspecialchars(CPANEL_DOMAIN) ?></div>
                    <div class="stat-label">Server Domain</div>
                </div>
            </div>
        </div>

        <div class="dashboard-grid">
            <div class="card">
                <div class="card-header">
                    <h2><i class="ri-list-check"></i> Account Directory</h2>
                </div>
                <div class="card-body">
                    <div class="email-list" id="email-list-container">
                         <div class="loading-state"><i class="ri-loader-4-line"></i><p>Loading Accounts...</p></div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2><i class="ri-user-add-line"></i> Create New Account</h2>
                </div>
                <div class="card-body">
                    <form id="email-form" autocomplete="off">
                        <input autocomplete="false" name="hidden" type="text" style="display:none;">
                        <div class="form-group">
                            <label class="form-label" for="email_user">Username</label>
                            <div class="input-group">
                                <input type="text" name="email_user" id="email_user" class="dashboard-form-input" required>
                                <span class="input-suffix">@<?= htmlspecialchars(CPANEL_DOMAIN) ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="password">Password</label>
                            <div class="password-input-container">
                                <input type="password" name="password" id="password" class="dashboard-form-input" autocomplete="new-password" required>
                                <button type="button" class="password-toggle" onclick="togglePassword(this, 'password')"><i class="ri-eye-line"></i></button>
                            </div>
                            <div class="password-strength-meter">
                                <div class="strength-bars"><div class="strength-bar"></div><div class="strength-bar"></div><div class="strength-bar"></div></div>
                                <div id="strength-text" class="strength-text"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="quota">Storage Quota (MB)</label>
                            <input type="number" id="quota" name="quota" class="dashboard-form-input" value="250" required>
                        </div>
                        <button type="submit" id="create-email-btn" class="btn btn-primary">
                            <i class="ri-mail-send-line"></i> <span class="btn-text">Create Email Account</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // --- COMPLETE JAVASCRIPT FUNCTIONS ---

        // 1. Toast Notification Function
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            if (!container) return;
            const toast = document.createElement('div');
            const iconClass = type === 'success' ? 'ri-checkbox-circle-line' : 'ri-error-warning-line';
            toast.className = `toast toast-${type}`;
            toast.innerHTML = `<i class="${iconClass}"></i><span>${message}</span>`;
            container.appendChild(toast);
            setTimeout(() => {
                toast.classList.add('closing');
                toast.addEventListener('animationend', () => toast.remove());
            }, 4000);
        }

        // 2. Password Toggle Function
        function togglePassword(button, inputId) {
            const passwordInput = document.getElementById(inputId);
            const icon = button.querySelector('i');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.replace('ri-eye-line', 'ri-eye-off-line');
            } else {
                passwordInput.type = 'password';
                icon.classList.replace('ri-eye-off-line', 'ri-eye-line');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const emailForm = document.getElementById('email-form');
            const emailListContainer = document.getElementById('email-list-container');
            const totalAccountsStat = document.getElementById('total-accounts-stat');
            const passwordInput = document.getElementById('password');
            const strengthBars = document.querySelectorAll('.strength-bar');
            const strengthText = document.getElementById('strength-text');

            // 3. Password Strength Meter Function
            function updatePasswordStrength() {
                const pass = passwordInput.value;
                let score = 0;
                if (!pass) {
                    strengthText.textContent = '';
                    strengthBars.forEach(bar => bar.className = 'strength-bar');
                    return;
                }
                if (pass.length >= 8) score++;
                if (/[A-Z]/.test(pass)) score++;
                if (/[0-9]/.test(pass)) score++;
                if (/[^A-Za-z0-9]/.test(pass)) score++;

                strengthBars.forEach(bar => bar.className = 'strength-bar');
                if (score <= 2) {
                    strengthBars[0].classList.add('weak');
                    strengthText.textContent = 'Weak';
                    strengthText.style.color = '#ef4444';
                } else if (score === 3) {
                    strengthBars[0].classList.add('medium');
                    strengthBars[1].classList.add('medium');
                    strengthText.textContent = 'Medium';
                    strengthText.style.color = '#f97316';
                } else {
                    strengthBars.forEach(bar => bar.classList.add('strong'));
                    strengthText.textContent = 'Strong';
                    strengthText.style.color = '#22c55e';
                }
            }
            passwordInput.addEventListener('input', updatePasswordStrength);
            
            // 4. Function to refresh the email list via AJAX
            async function refreshEmailList() {
                emailListContainer.innerHTML = `<div class="loading-state"><i class="ri-loader-4-line"></i><p>Loading Accounts...</p></div>`;
                try {
                    const response = await fetch('list_emails.php');
                    const data = await response.json();
                    if (data.status === 'success') {
                        emailListContainer.innerHTML = data.html;
                        totalAccountsStat.textContent = data.count;
                        if (data.count === 0) {
                            emailListContainer.innerHTML = `<div class="empty-state"><div class="empty-state-icon"><i class="ri-inbox-unarchive-line"></i></div><h3>No Accounts Found</h3><p>Create one to get started.</p></div>`;
                        }
                    } else {
                        emailListContainer.innerHTML = `<div class="empty-state"><div class="empty-state-icon"><i class="ri-error-warning-line"></i></div><h3>Error</h3><p>${data.message}</p></div>`;
                    }
                } catch (error) {
                    showToast('Could not refresh the email list.', 'error');
                    emailListContainer.innerHTML = `<div class="empty-state"><div class="empty-state-icon"><i class="ri-error-warning-line"></i></div><h3>Error</h3><p>Could not connect to the server.</p></div>`;
                }
            }
            
            // 5. AJAX Form Submission
            emailForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                const submitBtn = document.getElementById('create-email-btn');
                const btnText = submitBtn.querySelector('.btn-text');
                submitBtn.disabled = true;
                btnText.textContent = 'Creating...';
                
                const formData = new FormData(emailForm);
                try {
                    const response = await fetch('create_email.php', { method: 'POST', body: formData });
                    const result = await response.json();
                    if (result.status === 'success') {
                        showToast(result.message, 'success');
                        emailForm.reset();
                        updatePasswordStrength();
                        await refreshEmailList();
                    } else {
                        showToast(result.message, 'error');
                    }
                } catch (error) {
                    showToast('A server error occurred.', 'error');
                } finally {
                    submitBtn.disabled = false;
                    btnText.textContent = 'Create Email Account';
                }
            });

            // Initial list load on page enter
            refreshEmailList();
        });
    </script>
</body>
</html>

