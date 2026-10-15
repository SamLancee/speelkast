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
    public function usernameExists(string $username): bool {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE username = :username LIMIT 1");
        $stmt->execute(['username' => $username]);
        return (bool)$stmt->fetch();
    }

    public function register(string $username, string $plainPassword): bool {
        // Wachtwoord hashen met bcrypt
        $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        return $stmt->execute([
            'username' => $username,
            'password' => $hashedPassword
        ]);
    }
}