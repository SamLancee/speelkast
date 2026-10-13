<?php
// config/Database.php

class Database {
    public static function getConnection(): PDO {
        $host = 'localhost';
        $dbname = 'speelkast'; // naam van database
        $user = 'root';
        $pass = '';

        $dsn = "mysql:host={$host};dbname={$dbname}";

        return new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }
}