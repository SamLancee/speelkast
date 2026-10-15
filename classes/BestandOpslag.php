<?php
// classes/BestandOpslag.php
require_once __DIR__ . '/../interfaces/OpslagInterface.php';

class BestandOpslag implements OpslagInterface {
    // Vaste map, niet van buitenaf aan te passen
    private const DOEL_MAP = 'upload/';

    public function __construct() {
        // Direct controleren voor juiste map
        if (!is_dir(self::DOEL_MAP) || !is_writable(self::DOEL_MAP)) {
            throw new Exception("De uploadmap bestaat niet of heeft geen schrijfrechten.");
        }
    }

    public function slaOp(array $fileArray): string {
        if (!isset($fileArray['error']) || $fileArray['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Fout bij uploaden van de afbeelding (code: " . ($fileArray['error'] ?? 'onbekend') . ").");
        }

        $toegestaneExtensies = ['jpg', 'jpeg', 'png', 'gif'];
        $extensie = strtolower(pathinfo($fileArray['name'], PATHINFO_EXTENSION));

        if (!in_array($extensie, $toegestaneExtensies, true)) {
            throw new Exception("Alleen JPG, JPEG, PNG en GIF bestanden zijn toegestaan.");
        }

        // Unieke bestandsnaam genereren
        $uniekeNaam = uniqid('foto_', true) . '.' . $extensie;
        $doelPad = self::DOEL_MAP . $uniekeNaam;

        if (!move_uploaded_file($fileArray['tmp_name'], $doelPad)) {
            throw new Exception("Het bestand kon niet worden opgeslagen op de server.");
        }

        return $doelPad;
    }
}