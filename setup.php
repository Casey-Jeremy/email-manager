<?php
// Prevent access if the config file already exists.
if (file_exists(__DIR__ . '/config.php')) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup - Cpanel Email Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet" />
    <style>
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; 
            background-image: url('https://wallpapercave.com/wp/wp5603666.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex; 
            align-items: center; 
            justify-content: center; 
            min-height: 100vh; 
            padding: 20px; 
        }
        .setup-container { 
            width: 100%; 
            max-width: 650px; 
            padding: 40px; 
            border-radius: 16px; 

            /* Glassmorphism Effect */
            background: rgba(25, 25, 35, 0.45);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }
        h2 { font-size: 2rem; font-weight: 700; color: #FFFFFF; text-align: center; margin-bottom: 8px; }
        .subtitle { text-align: center; color: #E5E7EB; margin-bottom: 30px; }
        .step { display: none; }
        .step.active { display: block; animation: fadeIn 0.5s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        
        /* Form Styles */
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; margin-bottom: 8px; font-weight: 500; color: #D1D5DB; }
        .form-input { 
            width: 100%; 
            padding: 12px; 
            border-radius: 8px; 
            font-size: 1rem; 
            transition: all 0.2s ease;
            background-color: rgba(0,0,0,0.25);
            border: 1px solid rgba(255, 255, 255, 0.18);
            color: #F9FAFB;
            box-sizing: border-box;
        }
        .form-input::placeholder { color: #9CA3AF; }
        .form-input:focus { 
            outline: none; 
            border-color: #A855F7; 
            box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.3); 
        }
        
        /* Button Styles */
        .ai-button { display: inline-block; width: 100%; text-align:center; color: white; font-weight: bold; padding: 14px 24px; border-radius: 99px; transition: all 0.3s ease; transform: scale(1); border: none; cursor: pointer; background: linear-gradient(90deg, #4F46E5, #A855F7, #EC4899); box-shadow: 0 4px 15px 0 rgba(168, 85, 247, 0.3); }
        .ai-button:hover { transform: scale(1.03); box-shadow: 0 6px 20px 0 rgba(168, 85, 247, 0.4); }
        .button-group { display: flex; justify-content: space-between; margin-top: 30px; }
        .btn-secondary { background: rgba(255, 255, 255, 0.1); color: #F9FAFB; border: 1px solid rgba(255, 255, 255, 0.18); padding: 12px 20px; border-radius: 8px; font-weight: 500; cursor: pointer; }

        /* Branding Styles */
        .logo-uploader { text-align: center; }
        .logo-preview { width: 160px; height: 160px; margin: 0 auto; border: 2px dashed rgba(255, 255, 255, 0.3); border-radius: 8px; display: flex; align-items: center; justify-content: center; cursor: pointer; background-color: rgba(0,0,0,0.2); }
        .logo-preview img { max-width: 100%; max-height: 100%; object-fit: contain; padding: 8px; }
        .color-picker { display: flex; justify-content: center; gap: 10px; flex-wrap: wrap; margin-top: 15px; }
        .color-option { width: 40px; height: 40px; border-radius: 50%; cursor: pointer; border: 2px solid #E5E7EB; transition: all 0.2s ease; }
        .color-option.selected { border-color: #EC4899; transform: scale(1.1); }
        #error-message { color: #FCA5A5; text-align: center; margin-top: 15px; font-weight: 500; height: 1em; }
        #logo-placeholder { color: #D1D5DB; }
    </style>
</head>
<body>
    <div class="setup-container">
        <form id="setup-form" action="save_config.php" method="POST" enctype="multipart/form-data">
            
            <!-- Step 1: Welcome -->
            <div id="step-1" class="step active">
                <h2>Welcome to Setup</h2>
                <p class="subtitle">Let's get your secure email dashboard ready.</p>
                <p style="color: #E5E7EB; margin-bottom: 20px; text-align: center;">This tool helps you manage cPanel emails without full control panel access. Your credentials will be stored in a `config.php` file on your server.</p>
                <button type="button" class="ai-button" onclick="nextStep()">Get Started <i class="ri-arrow-right-line"></i></button>
            </div>

            <!-- Step 2: Credentials -->
            <div id="step-2" class="step">
                <h2>Enter cPanel Details</h2>
                <p class="subtitle">This information is required to connect to your server.</p>
                <div class="form-group"><label class="form-label" for="cpanel_user">cPanel Username</label><input type="text" id="cpanel_user" name="cpanel_user" class="form-input" required></div>
                <div class="form-group"><label class="form-label" for="cpanel_password">cPanel Password</label><input type="password" id="cpanel_password" name="cpanel_password" class="form-input" required></div>
                <div class="form-group"><label class="form-label" for="cpanel_token">cPanel API Token (Optional)</label><input type="text" id="cpanel_token" name="cpanel_token" class="form-input"></div>
                <div class="form-group"><label class="form-label" for="cpanel_domain">Your Domain</label><input type="text" id="cpanel_domain" name="cpanel_domain" class="form-input" placeholder="example.com" required></div>
                <div id="error-message"></div>
                <div class="button-group"><button type="button" class="btn-secondary" onclick="prevStep()"><i class="ri-arrow-left-line"></i> Back</button><button type="button" class="ai-button" style="width:auto;" onclick="nextStep()">Next: Customize <i class="ri-arrow-right-line"></i></button></div>
            </div>

            <!-- Step 3: Branding -->
            <div id="step-3" class="step">
                <h2>Customize Dashboard</h2>
                <p class="subtitle">Personalize the look of your email manager.</p>
                <div class="logo-uploader form-group">
                    <label class="form-label" style="text-align: center;">Upload Your Logo</label>
                    <div class="logo-preview" onclick="document.getElementById('logo-input').click();">
                        <img id="logo-preview-img" src="#" alt="Logo Preview" style="display:none;"><span id="logo-placeholder">Click to upload</span>
                    </div>
                    <input type="file" id="logo-input" name="logo" accept="image/*" style="display:none;">
                </div>
                <div class="form-group">
                    <label class="form-label" style="text-align:center;">Choose Dashboard Background</label>
                    <div class="color-picker">
                        <?php $colors = ['#111827', '#F3F4F6', '#DBEAFE', '#FEE2E2', '#D1FAE5', '#FEF3C7']; ?>
                        <?php foreach($colors as $index => $color): ?>
                            <div class="color-option <?= $index === 0 ? 'selected' : '' ?>" style="background-color: <?= $color; ?>" data-color="<?= $color; ?>"></div>
                        <?php endforeach; ?>
                    </div>
                    <input type="hidden" name="bg_color" id="bg_color_input" value="#111827">
                </div>
                <div class="button-group"><button type="button" class="btn-secondary" onclick="prevStep()"><i class="ri-arrow-left-line"></i> Back</button><button type="submit" class="ai-button" style="width:auto;">Finish Setup <i class="ri-check-line"></i></button></div>
            </div>
        </form>
    </div>
    <script>
        let currentStep = 1;
        const steps = document.querySelectorAll('.step');
        function showStep(stepNumber) { steps.forEach(step => step.classList.remove('active')); document.getElementById(`step-${stepNumber}`).classList.add('active'); }
        function nextStep() {
            if (currentStep === 2) {
                const user = document.getElementById('cpanel_user').value, pass = document.getElementById('cpanel_password').value, domain = document.getElementById('cpanel_domain').value;
                if (!user || !pass || !domain) { document.getElementById('error-message').textContent = 'Username, Password, and Domain are required.'; return; }
                document.getElementById('error-message').textContent = '';
            }
            if (currentStep < steps.length) { currentStep++; showStep(currentStep); }
        }
        function prevStep() { if (currentStep > 1) { currentStep--; showStep(currentStep); } }
        document.getElementById('logo-input').addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('logo-preview-img').src = event.target.result;
                    document.getElementById('logo-preview-img').style.display = 'block';
                    document.getElementById('logo-placeholder').style.display = 'none';
                }
                reader.readAsDataURL(e.target.files[0]);
            }
        });
        document.querySelectorAll('.color-option').forEach(opt => {
            opt.addEventListener('click', function() {
                document.querySelectorAll('.color-option').forEach(o => o.classList.remove('selected'));
                this.classList.add('selected');
                document.getElementById('bg_color_input').value = this.dataset.color;
            });
        });
    </script>
</body>
</html>

