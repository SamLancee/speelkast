<!-- views/login_view.php -->
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inloggen - De Speelkast</title>
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>

    <div class="auth-card">
        <h2>Inloggen</h2>

        <?php if (isset($_GET['registered'])): ?>
            <div class="alert alert-success">Account aangemaakt! Je kunt nu inloggen.</div>
        <?php endif; ?>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="username">Gebruikersnaam:</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($usernameInput ?? '') ?>" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Wachtwoord:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn btn-primary">Inloggen</button>
        </form>

        <div class="auth-divider">
            <span>of</span>
        </div>

        <a href="register.php" class="btn btn-secondary">Account aanmaken</a>
    </div>

</body>
</html>