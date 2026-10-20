<?php
$host = 'localhost';
$dbname = 'speelkast';
$username = 'Sam';
$password = '123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;", $username, 
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION));

    $stmt = $pdo->prepare("SELECT * FROM items");
    $stmt-> execute();
    
}