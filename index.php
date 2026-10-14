<?php
// index.php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

require_once 'config/Database.php';
require_once 'classes/ItemRepository.php';

$currentUser = $_SESSION['user'];

$db = Database::getConnection();
$itemRepo = new ItemRepository($db); // Dependency Injection
$items = $itemRepo->getAll();

require_once 'views/kast_view.php';