<!-- views/register_view.php -->
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registreren - De Speelkast</title>
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>

    <div class="auth-card">
        <h2>Account aanmaken</h2>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <div class="form-group">
                <label for="username">Gebruikersnaam:</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($usernameInput ?? '') ?>" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Wachtwoord:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Bevestig wachtwoord:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <button type="submit" class="btn btn-primary">Registreren</button>
        </form>

        <div class="auth-divider">
            <span>of</span>
        </div>

        <a href="login.php" class="btn btn-secondary">Al een account? Log in</a>
    </div>

</body>
</html>