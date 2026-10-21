<!-- views/kast_view.php -->
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>De Speelkast</title>
    <link rel="stylesheet" href="css/kast.css">
</head>
<body>

    <header>
        <div class="user-status">
            Ingelogd als: <strong><?= htmlspecialchars($currentUser['username']) ?></strong>
        </div>
        <nav>
            <a href="toevoegen.php" class="btn-primary">+ Item toevoegen</a>
            <a href="export.php" class="btn-link">Avondlijst (XML/XSLT)</a>
            <a href="logout.php" class="btn-link">Uitloggen</a>
        </nav>
    </header>

    <div class="container">
        <div class="page-header">
            <h2>Kastinhoud</h2>
            <span class="item-count"><?= count($items) ?> item(s)</span>
        </div>

        <?php if (empty($items)): ?>
            <div class="empty-state">
                <p>Er staan momenteel nog geen spellen in de kast.</p>
                <p style="margin-top: 8px;">
                    <a href="toevoegen.php" style="color: var(--primary);">Voeg het eerste spel toe</a>
                </p>
            </div>
        <?php else: ?>
            <div class="grid">
                <?php foreach ($items as $item): ?>
                    <?php 
                        $type = strtolower($item->getType()); 
                        $badgeClass = ($type === 'uitbreiding') ? 'badge-uitbreiding' : 'badge-bordspel';
                    ?>
                    <div class="card">
                        <div class="card-img-wrapper">
                            <?= $item->showImage() ?>
                        </div>
                        <div class="card-body">
                            <div class="card-header-row">
                                <h3 class="card-title"><?= htmlspecialchars($item->getTitel()) ?></h3>
                                <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($item->getType()) ?></span>
                            </div>
                            <p class="card-desc"><?= htmlspecialchars($item->omschrijving()) ?></p>
                            <div class="card-footer">
                                <span>Eigenaar</span>
                                <strong><?= htmlspecialchars($item->getEigenaar()) ?></strong>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>