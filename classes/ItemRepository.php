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
    /**
     * Genereert een dynamische XML-structuur via de KastItem-objecten (OOP).
     */
    public function exportToXml(): string {
        // 1. Haal alle items op als echte objecten (Bordspel / Uitbreiding)
        $items = $this->getAll(); 

        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $root = $dom->createElement('speelkast');
        $dom->appendChild($root);

        // 2. Itereer over de objecten en roep hun eigen methodes aan
        foreach ($items as $item) {
            /** @var KastItem $item */
            $itemNode = $item->toXmlNode($dom);
            $root->appendChild($itemNode);
        }

        return $dom->saveXML();
    
    }
    
    public function createFromInput(array $postData, array $fileData, string $eigenaar): array {
        $errors = [];

        $type = $postData['type'] ?? 'bordspel';
        $titel = trim($postData['titel'] ?? '');
        $minSpelers = trim($postData['min_spelers'] ?? '');
        $maxSpelers = trim($postData['max_spelers'] ?? '');
        $speelduur = trim($postData['speelduur'] ?? '');
        $moeilijkheidsgraad = trim($postData['moeilijkheidsgraad'] ?? '');
        $basisspel = trim($postData['basisspel'] ?? '');

        // 1. Algemene validatie
        if (empty($titel)) {
            $errors[] = "Titel is verplicht.";
        } elseif (!preg_match('/^[a-zA-Z0-9\s\-:\.\',!?]+$/u', $titel)) {
            $errors[] = "Titel bevat ongeldige tekens.";
        }

        if (!in_array($type, ['bordspel', 'uitbreiding'], true)) {
            $errors[] = "Ongeldig type geselecteerd.";
        }

        // 2. Type-specifieke validatie
        if ($type === 'bordspel') {
            if ($minSpelers !== '' && (!ctype_digit($minSpelers) || (int)$minSpelers < 1)) {
                $errors[] = "Minimaal aantal spelers moet een positief getal zijn.";
            }
            if ($maxSpelers !== '' && (!ctype_digit($maxSpelers) || (int)$maxSpelers < 1)) {
                $errors[] = "Maximaal aantal spelers moet een positief getal zijn.";
            }
            if ($minSpelers !== '' && $maxSpelers !== '' && (int)$minSpelers > (int)$maxSpelers) {
                $errors[] = "Minimaal aantal spelers mag niet groter zijn dan maximaal aantal spelers.";
            }
            if ($speelduur !== '' && (!ctype_digit($speelduur) || (int)$speelduur < 1)) {
                $errors[] = "Speelduur moet een positief aantal minuten zijn.";
            }
        } elseif ($type === 'uitbreiding') {
            if (empty($basisspel)) {
                $errors[] = "Bij een uitbreiding is het basisspel verplicht.";
            }
        }

        // 3. Bestandscontrole
        if (!isset($fileData['error']) || $fileData['error'] === UPLOAD_ERR_NO_FILE) {
            $errors[] = "Selecteer een doosfoto om te uploaden.";
        }

        // Als er validatiefouten zijn, direct stoppen
        if (!empty($errors)) {
            return $errors;
        }

        // 4. Uitvoeren en opslaan
        try {
            $opslag = new BestandOpslag();
            $fotoPad = $opslag->slaOp($fileData);

            if ($type === 'bordspel') {
                $item = new Bordspel(
                    $titel,
                    $eigenaar,
                    $fotoPad,
                    $minSpelers !== '' ? (int)$minSpelers : null,
                    $maxSpelers !== '' ? (int)$maxSpelers : null,
                    $speelduur !== '' ? (int)$speelduur : null,
                    $moeilijkheidsgraad !== '' ? $moeilijkheidsgraad : null
                );
            } else {
                $item = new Uitbreiding(
                    $titel,
                    $eigenaar,
                    $fotoPad,
                    $basisspel
                );
            }

            $this->save($item);

        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }

        return $errors;
    }
}