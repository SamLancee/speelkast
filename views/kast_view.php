<!-- views/kast_view.php -->
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>De Speelkast</title>
</head>
<body>

    <header>
        <p>Ingelogd als: <strong><?= htmlspecialchars($currentUser['username']) ?></strong></p>
        <p>
            <a href="logout.php">Uitloggen</a> | 
            <a href="toevoegen.php">Item toevoegen</a> | 
            <a href="export.php">Avondlijst (XML/XSLT)</a>
        </p>
    </header>
    <hr>

    <h2>Kastinhoud</h2>

    <?php if (empty($items)): ?>
        <p>Er staan momenteel nog geen spellen in de kast.</p>
    <?php else: ?>
        <?php foreach ($items as $item): ?>
            <div>
                <?= $item->showImage() ?><br>
                <strong>Type:</strong> <?= htmlspecialchars($item->getType()) ?><br>
                <strong>Titel:</strong> <?= htmlspecialchars($item->getTitel()) ?><br>
                <strong>Omschrijving:</strong> <?= htmlspecialchars($item->omschrijving()) ?><br>
                <strong>Eigenaar:</strong> <?= htmlspecialchars($item->getEigenaar()) ?>
            </div>
            <hr>
        <?php endforeach; ?>
    <?php endif; ?>

</body>
</html>