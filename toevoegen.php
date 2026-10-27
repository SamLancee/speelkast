<?php
// toevoegen.php (Controller)
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

// Formulierwaarden opvangen zodat ze in het formulier blijven staan
$type = $_POST['type'] ?? 'bordspel';
$titel = trim($_POST['titel'] ?? '');
$minSpelers = trim($_POST['min_spelers'] ?? '');
$maxSpelers = trim($_POST['max_spelers'] ?? '');
$speelduur = trim($_POST['speelduur'] ?? '');
$moeilijkheidsgraad = trim($_POST['moeilijkheidsgraad'] ?? '');
$basisspel = trim($_POST['basisspel'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = Database::getConnection();
    $itemRepo = new ItemRepository($db);

    // Laat het model de validatie en opslag afhandelen
    $errors = $itemRepo->createFromInput($_POST, $_FILES['foto'] ?? [], $currentUser['username']);

    // Als er geen fouten zijn, is het item opgeslagen
    if (empty($errors)) {
        header('Location: index.php');
        exit;
    }
}

// Laad de HTML-view (met de fouten en ingevulde waarden)
require_once 'views/toevoegen_view.php';