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
   
   //opslaan
    public function save(KastItem $item): bool {
        $isUitbreiding = ($item instanceof Uitbreiding);
        
        $sql = "INSERT INTO items (
                    type, titel, eigenaar, foto, 
                    min_spelers, max_spelers, speelduur, moeilijkheidsgraad, basisspel
                ) VALUES (
                    :type, :titel, :eigenaar, :foto, 
                    :min_spelers, :max_spelers, :speelduur, :moeilijkheidsgraad, :basisspel
                )";

        $stmt = $this->db->prepare($sql);

        if ($isUitbreiding) {
            return $stmt->execute([
                'type'               => 'uitbreiding',
                'titel'              => $item->getTitel(),
                'eigenaar'           => $item->getEigenaar(),
                'foto'               => $item->getFotoPad(),
                'min_spelers'        => null,
                'max_spelers'        => null,
                'speelduur'          => null,
                'moeilijkheidsgraad' => null,
                'basisspel'          => method_exists($item, 'getBasisspel') ? $item->getBasisspel() : null
            ]);
        } else {
            // Het is een Bordspel
            return $stmt->execute([
                'type'               => 'bordspel',
                'titel'              => $item->getTitel(),
                'eigenaar'           => $item->getEigenaar(),
                'foto'               => $item->getFotoPad(),
                'min_spelers'        => method_exists($item, 'getMinSpelers') ? $item->getMinSpelers() : null,
                'max_spelers'        => method_exists($item, 'getMaxSpelers') ? $item->getMaxSpelers() : null,
                'speelduur'          => method_exists($item, 'getSpeelduur') ? $item->getSpeelduur() : null,
                'moeilijkheidsgraad' => method_exists($item, 'getMoeilijkheidsgraad') ? $item->getMoeilijkheidsgraad() : null,
                'basisspel'          => null
            ]);
        }
    }
    public function exportToXml(): string {
        $stmt = $this->db->query("SELECT * FROM items ORDER BY id ASC");
        $items = $stmt->fetchAll();

        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $root = $dom->createElement('speelkast');
        $dom->appendChild($root);

        foreach ($items as $item) {
            $itemElem = $dom->createElement('item');
            $itemElem->setAttribute('type', $item['type']);

            // Type-specifieke attributen toevoegen (alleen als ze niet NULL of leeg zijn)
            if (!empty($item['speelduur'])) {
                $itemElem->setAttribute('speelduur', (string)$item['speelduur']);
            }
            if (!empty($item['min_spelers']) && !empty($item['max_spelers'])) {
                $itemElem->setAttribute('spelers', $item['min_spelers'] . '-' . $item['max_spelers']);
            }
            if (!empty($item['moeilijkheidsgraad'])) {
                $itemElem->setAttribute('moeilijkheidsgraad', $item['moeilijkheidsgraad']);
            }

            // Kind-elementen
            $titelElem = $dom->createElement('titel');
            $titelElem->textContent = $item['titel'];
            $itemElem->appendChild($titelElem);

            if ($item['type'] === 'uitbreiding' && !empty($item['basisspel'])) {
                $basisElem = $dom->createElement('basisspel');
                $basisElem->textContent = $item['basisspel'];
                $itemElem->appendChild($basisElem);
            }

            $fotoElem = $dom->createElement('foto');
            $fotoElem->textContent = $item['foto'];
            $itemElem->appendChild($fotoElem);

            $eigenaarElem = $dom->createElement('eigenaar');
            $eigenaarElem->textContent = $item['eigenaar'];
            $itemElem->appendChild($eigenaarElem);

            $root->appendChild($itemElem);
        }

        return $dom->saveXML();
    }
}