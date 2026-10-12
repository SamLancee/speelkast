<?php


class UserRepository {
    private PDO $db;

    // Dependency injection: PDO wordt via de constructor doorgegeven
    public function __construct(PDO $db) {
        $this->db = $db;
    }

    //check gebruikersnaam en wachtwoord
    public function authenticate(string $username, string $password): ?array {
        $stmt = $this->db->prepare("SELECT id, username, password FROM users WHERE username = :username LIMIT 1");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return [
                'id'       => $user['id'],
                'username' => $user['username']
            ];
        }

        return null;
    }
}