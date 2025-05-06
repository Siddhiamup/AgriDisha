<?php
// Ensure this file is included in the main dashboard
if (!defined('DB_HOST')) {
    exit('Direct access denied');
}

// Process settings form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_settings'])) {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('CSRF token validation failed');
    }

    // Sanitize and validate input
    $email_notifications = isset($_POST['email_notifications']) ? 1 : 0;
    $sms_notifications = isset($_POST['sms_notifications']) ? 1 : 0;
    $language = filter_input(INPUT_POST, 'language', FILTER_SANITIZE_STRING);
    $timezone = filter_input(INPUT_POST, 'timezone', FILTER_SANITIZE_STRING);

    // Update settings in database
    $update_query = $conn->prepare("
        INSERT INTO user_settings (user_id, email_notifications, sms_notifications, language, timezone)
        VALUES (?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
        email_notifications = VALUES(email_notifications),
        sms_notifications = VALUES(sms_notifications),
        language = VALUES(language),
        timezone = VALUES(timezone)
    ");

    $update_query->bind_param("iiiss", $buyer_id, $email_notifications, $sms_notifications, $language, $timezone);
    
    if ($update_query->execute()) {
        $success_message = "Settings updated successfully!";
    } else {
        $error_message = "Error updating settings: " . $conn->error;
    }
}

// Fetch current settings
$settings_query = $conn->prepare("SELECT * FROM user_settings WHERE user_id = ?");
$settings_query->bind_param("i", $buyer_id);
$settings_query->execute();
$settings_result = $settings_query->get_result();
$settings = $settings_result->fetch_assoc() ?: [
    'email_notifications' => 1,
    'sms_notifications' => 1,
    'language' => 'en',
    'timezone' => 'UTC'
];
?>

<div class="settings-container">
    <h2>Account Settings</h2>
    
    <?php if (isset($success_message)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
    <?php endif; ?>
    
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>

    <form method="POST" action="?tab=settings" class="settings-form">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        
        <div class="settings-section">
            <h3>Notifications</h3>
            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="email_notifications" 
                           <?= $settings['email_notifications'] ? 'checked' : '' ?>>
                    Email Notifications
                </label>
            </div>
            
            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="sms_notifications"
                           <?= $settings['sms_notifications'] ? 'checked' : '' ?>>
                    SMS Notifications
                </label>
            </div>
        </div>

        <div class="settings-section">
            <h3>Preferences</h3>
            <div class="form-group">
                <label for="language">Language</label>
                <select name="language" id="language" class="form-control">
                    <option value="en" <?= $settings['language'] === 'en' ? 'selected' : '' ?>>English</option>
                    <option value="es" <?= $settings['language'] === 'es' ? 'selected' : '' ?>>Spanish</option>
                    <option value="fr" <?= $settings['language'] === 'fr' ? 'selected' : '' ?>>French</option>
                </select>
            </div>

            <div class="form-group">
                <label for="timezone">Timezone</label>
                <select name="timezone" id="timezone" class="form-control">
                    <?php
                    $timezones = DateTimeZone::listIdentifiers();
                    foreach ($timezones as $tz) {
                        $selected = $settings['timezone'] === $tz ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($tz) . "' $selected>" . 
                             htmlspecialchars($tz) . "</option>";
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="settings-section">
            <h3>Security</h3>
            <div class="form-group">
                <a href="change_password.php" class="btn btn-secondary">Change Password</a>
            </div>
            <div class="form-group">
                <a href="two_factor_auth.php" class="btn btn-secondary">Setup Two-Factor Authentication</a>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" name="update_settings" class="btn btn-primary">Save Changes</button>
        </div>
    </form>
</div>

<style>
.settings-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

.settings-section {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.form-group {
    margin-bottom: 15px;
}

.form-control {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 10px;
}

.btn {
    padding: 10px 20px;
    border-radius: 4px;
    border: none;
    cursor: pointer;
    font-weight: 500;
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-secondary {
    background: #6c757d;
    color: white;
    text-decoration: none;
    display: inline-block;
}

.alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 4px;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
</style>