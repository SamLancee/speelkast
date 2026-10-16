<?php
// toevoegen.php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

require_once 'config/Database.php';
require_once 'classes/ItemRepository.php';
require_once 'classes/BestandOpslag.php';
require_once 'classes/Bordspel.php';
require_once 'classes/Uitbreiding.php';

$currentUser = $_SESSION['user'];
$errors = [];

// Formulierwaarden onthouden bij fouten
$type = $_POST['type'] ?? 'bordspel';
$titel = trim($_POST['titel'] ?? '');
$minSpelers = trim($_POST['min_spelers'] ?? '');
$maxSpelers = trim($_POST['max_spelers'] ?? '');
$speelduur = trim($_POST['speelduur'] ?? '');
$moeilijkheidsgraad = trim($_POST['moeilijkheidsgraad'] ?? '');
$basisspel = trim($_POST['basisspel'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Server-side validatie (algemeen)
    if (empty($titel)) {
        $errors[] = "Titel is verplicht.";
    } elseif (!preg_match('/^[a-zA-Z0-9\s\-:\.\',!?]+$/u', $titel)) {
        $errors[] = "Titel bevat ongeldige tekens.";
    }

    // Controle op type
    if (!in_array($type, ['bordspel', 'uitbreiding'], true)) {
        $errors[] = "Ongeldig type geselecteerd.";
    }

    // 2. Type-specifieke validatie
    if ($type === 'bordspel') {
        if ($minSpelers !== '' && (!ctype_digit($minSpelers) || (int)$minSpelers < 1)) {
            $errors[] = "Minimaal aantal spelers moet een positief getal zijn.";
        }
        if ($maxSpelers !== '' && (!ctype_digit($maxSpelers) || (int)$maxSpelers < 1)) {
            $errors[] = "Maximaal aantal spelers moet een positief getal zijn.";
        }
        if ($minSpelers !== '' && $maxSpelers !== '' && (int)$minSpelers > (int)$maxSpelers) {
            $errors[] = "Minimaal aantal spelers mag niet groter zijn dan maximaal aantal spelers.";
        }
        if ($speelduur !== '' && (!ctype_digit($speelduur) || (int)$speelduur < 1)) {
            $errors[] = "Speelduur moet een positief aantal minuten zijn.";
        }
    } elseif ($type === 'uitbreiding') {
        if (empty($basisspel)) {
            $errors[] = "Bij een uitbreiding is het basisspel verplicht.";
        }
    }

    // 3. Bestandscontrole
    if (!isset($_FILES['foto']) || $_FILES['foto']['error'] === UPLOAD_ERR_NO_FILE) {
        $errors[] = "Selecteer een doosfoto om te uploaden.";
    }

    // 4. Als er geen validatiefouten zijn: opslaan!
    if (empty($errors)) {
        try {
            $opslag = new BestandOpslag('upload/');
            $fotoPad = $opslag->slaOp($_FILES['foto']);

            // Maak het juiste object aan
            if ($type === 'bordspel') {
                $item = new Bordspel(
                    $titel,
                    $currentUser['username'], // De ingelogde gebruiker wordt eigenaar
                    $fotoPad,
                    $minSpelers !== '' ? (int)$minSpelers : null,
                    $maxSpelers !== '' ? (int)$maxSpelers : null,
                    $speelduur !== '' ? (int)$speelduur : null,
                    $moeilijkheidsgraad !== '' ? $moeilijkheidsgraad : null
                );
            } else {
                $item = new Uitbreiding(
                    $titel,
                    $currentUser['username'],
                    $fotoPad,
                    $basisspel
                );
            }

            $db = Database::getConnection();
            $itemRepo = new ItemRepository($db);
            $itemRepo->save($item);

            // Terug naar het overzicht
            header('Location: index.php');
            exit;

        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }
    }
}

require_once 'views/toevoegen_view.php';