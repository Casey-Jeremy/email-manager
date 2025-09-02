<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || file_exists(__DIR__ . '/config.php')) {
    http_response_code(403);
    die('Forbidden');
}

$cpanel_user = trim($_POST['cpanel_user']);
$cpanel_password = trim($_POST['cpanel_password']);
$cpanel_token = trim($_POST['cpanel_token']);
$cpanel_domain = trim($_POST['cpanel_domain']);
$bg_color = trim($_POST['bg_color']);
$logo_path = ''; // Initialize logo path

$upload_dir = __DIR__ . '/assets/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
    $allowed_types = ['image/png', 'image/jpeg', 'image/gif', 'image/svg+xml'];
    $file_info = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($file_info, $_FILES['logo']['tmp_name']);
    finfo_close($file_info);

    if (in_array($mime_type, $allowed_types)) {
        $extension = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
        $new_filename = 'logo.' . strtolower($extension);
        $logo_path = 'assets/' . $new_filename;
        if (!move_uploaded_file($_FILES['logo']['tmp_name'], __DIR__ . '/' . $logo_path)) {
            $logo_path = ''; // Clear path on upload failure
        }
    }
}

$config_content = "<?php\n\n";
$config_content .= "// Cpanel Email Manager Configuration\n";
$config_content .= "define('CPANEL_USER', '" . addslashes($cpanel_user) . "');\n";
$config_content .= "define('CPANEL_PASSWORD', '" . addslashes($cpanel_password) . "');\n";
$config_content .= "define('CPANEL_TOKEN', '" . addslashes($cpanel_token) . "');\n";
$config_content .= "define('CPANEL_DOMAIN', '" . addslashes($cpanel_domain) . "');\n\n";
$config_content .= "define('LOGO_PATH', '" . addslashes($logo_path) . "');\n";
$config_content .= "define('BG_COLOR', '" . addslashes($bg_color) . "');\n\n";
$config_content .= "?>";

if (file_put_contents(__DIR__ . '/config.php', $config_content)) {
    header('Location: index.php?setup=success');
    exit;
} else {
    die('Error: Could not write to config.php. Please check file permissions.');
}

