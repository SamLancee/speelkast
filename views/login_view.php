<!-- views/login_view.php -->
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Inloggen - De Speelkast</title>
    <style>
        body { font-family: sans-serif; margin: 40px; }
        .error { color: #b00020; background: #fde8e8; border: 1px solid #f8b4b4; padding: 10px; width: 300px; margin-bottom: 15px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], input[type="password"] { width: 300px; padding: 8px; }
        button { padding: 8px 16px; cursor: pointer; }
    </style>
</head>
<body>
    <h2>Inloggen bij De Speelkast</h2>

    <?php if (!empty($errorMessage)): ?>
        <div class="error"><?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <div class="form-group">
            <label for="username">Gebruikersnaam:</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($usernameInput ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="password">Wachtwoord:</label>
            <input type="password" id="password" name="password" required>
        </div>

        <button type="submit">Inloggen</button>
    </form>
</body>
</html>