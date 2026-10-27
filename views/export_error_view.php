<!-- views/export_error_view.php -->
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>XSD Validatiefouten - De Speelkast</title>
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>
    <div class="auth-card" style="max-width: 550px;">
        <h2>XSD Validatiefouten</h2>
        <div class="alert alert-error">
            De gegenereerde XML voldoet niet aan het schema:
        </div>
        <ul style="margin: 0 0 20px 20px; color: var(--text-main); font-size: 0.9rem;">
            <?php foreach ($xsdErrors as $err): ?>
                <li><?= htmlspecialchars($err) ?></li>
            <?php endforeach; ?>
        </ul>
        <a href="index.php" class="btn btn-secondary">&larr; Terug naar de kast</a>
    </div>
</body>
</html>