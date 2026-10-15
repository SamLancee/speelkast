<!-- views/register_view.php -->
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Registreren - De Speelkast</title>
</head>
<body>
    <h2>Registreren</h2>

    <?php if (!empty($errorMessage)): ?>
        <p><strong>Fout:</strong> <?= htmlspecialchars($errorMessage) ?></p>
    <?php endif; ?>

    <form method="POST" action="register.php">
        <p>
            <label for="username">Gebruikersnaam:</label><br>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($usernameInput) ?>" required>
        </p>

        <p>
            <label for="password">Wachtwoord:</label><br>
            <input type="password" id="password" name="password" required>
        </p>

        <p>
            <label for="confirm_password">Bevestig wachtwoord:</label><br>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </p>

        <p>
            <button type="submit">Registreren</button>
        </p>
    </form>

    <p>Al een account? <a href="login.php">Log hier in</a>.</p>
</body>
</html>