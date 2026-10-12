<?php

session_start();

//Check voor inlog
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

//Bezoeker is al ingelogd
$currentUser = $_SESSION['user'];

// Moet nog itemrepository komen maar dat komt later
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>De Speelkast</title>
</head>
<body>
    <header>
        <p>Ingelogd als: <strong><?= htmlspecialchars($currentUser['username']) ?></strong></p>
        <p><a href="logout.php">Uitloggen</a> | <a href="toevoegen.php">Item toevoegen</a> | <a href="export.php">Avondlijst (XML/XSLT)</a></p>
    </header>
    <hr>

    <h2>Welkom bij De Speelkast!</h2>
    <p>Hier komt het overzicht van alle bordspellen en uitbreidingen.</p>
</body>
</html>