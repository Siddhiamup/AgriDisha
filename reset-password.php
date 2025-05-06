<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>

    <h1>Reset Password</h1>

    <form method="post" action="process-reset-password.php">
    <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

    <label for="password">New Password</label>
    <input type="password" id="password" name="password" required>

    <label for="password_confirmation">Repeat Password</label>
    <input type="password" id="password_confirmation" name="password_confirmation" required>

    <button type="submit">Send</button>
</form>


</body>
</html>