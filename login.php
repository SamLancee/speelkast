<?php
// login.php
session_start();

// Als de gebruiker al ingelogd is, direct doorsturen naar de kast
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

    if ($usernameInput === '' || $passwordInput === '') {
        $errorMessage = 'Vul zowel je gebruikersnaam als wachtwoord in.';
    } else {
        try {
            $db = Database::getConnection();
            $userRepo = new UserRepository($db); // Dependency Injection

            $user = $userRepo->authenticate($usernameInput, $passwordInput);

            if ($user !== null) {
                // Sla alleen veilige data op in session (geen wachtwoorden!)
                $_SESSION['user'] = [
                    'id'       => $user['id'],
                    'username' => $user['username']
                ];

                header('Location: index.php');
                exit;
            } else {
                $errorMessage = 'Onjuiste gebruikersnaam of wachtwoord.';
            }
        } catch (PDOException $e) {
            $errorMessage = 'Er ging iets mis met de databaseverbinding.';
        }
    }
}

// Render de view
require_once 'views/login_view.php';