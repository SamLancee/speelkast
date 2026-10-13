<?php
require_once 'config/Database.php';

$db = Database::getConnection();

$password = '123';
$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $db->prepare("UPDATE users SET password = :hash WHERE username = 'Test'");
$stmt->execute(['hash' => $hash]);

echo "Hash succesvol bijgewerkt naar: " . htmlspecialchars($hash) . "<br>";

// Direct testen of password_verify het goedkeurt
if (password_verify('123', $hash)) {
    echo "password_verify werkt 100%!";
} else {
    echo "Er gaat iets mis met password_verify.";
}