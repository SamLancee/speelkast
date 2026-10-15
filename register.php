<?php
// register.php
session_start();

// Als je al ingelogd bent, door naar de kast
if (isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

require_once 'config/Database.php';
require_once 'classes/UserRepository.php';

$errorMessage = '';
$usernameInput = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameInput = trim($_POST['username'] ?? '');
    $passwordInput = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($usernameInput === '' || $passwordInput === '') {
        $errorMessage = 'Vul alle velden in.';
    } elseif ($passwordInput !== $confirmPassword) {
        $errorMessage = 'De wachtwoorden komen niet overeen.';
    } else {
        try {
            $db = Database::getConnection();
            $userRepo = new UserRepository($db);

            if ($userRepo->usernameExists($usernameInput)) {
                $errorMessage = 'Deze gebruikersnaam is al in gebruik.';
            } else {
                if ($userRepo->register($usernameInput, $passwordInput)) {
                    //Direct inloggen
                    $_SESSION['user'] = [
                        'id'       => (int)$db->lastInsertId(),
                        'username' => $usernameInput
                    ];

                   
                    header('Location: index.php');
                    exit;
                } else {
                    $errorMessage = 'Er ging iets mis bij het registreren.';
                }
            }
        } catch (PDOException $e) {
            $errorMessage = 'Databasefout bij registratie.';
        }
    }
}

require_once 'views/register_view.php';