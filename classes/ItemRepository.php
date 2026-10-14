<?php
// classes/ItemRepository.php
require_once __DIR__ . '/Bordspel.php';
require_once __DIR__ . '/Uitbreiding.php';

class ItemRepository {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * @return KastItem[]
     */
    public function getAll(): array {
        $stmt = $this->db->query("SELECT * FROM items ORDER BY id DESC");
        $rows = $stmt->fetchAll();

        $items = [];
        foreach ($rows as $row) {
            if ($row['type'] === 'uitbreiding') {
                $items[] = new Uitbreiding(
                    $row['titel'],
                    $row['eigenaar'],
                    $row['foto'],
                    $row['basisspel'] ?? null,
                    (int)$row['id']
                );
            } else {
                $items[] = new Bordspel(
                    $row['titel'],
                    $row['eigenaar'],
                    $row['foto'],
                    $row['min_spelers'] !== null ? (int)$row['min_spelers'] : null,
                    $row['max_spelers'] !== null ? (int)$row['max_spelers'] : null,
                    $row['speelduur'] !== null ? (int)$row['speelduur'] : null,
                    $row['moeilijkheidsgraad'] ?? null,
                    (int)$row['id']
                );
            }
        }

        return $items;
    }
}