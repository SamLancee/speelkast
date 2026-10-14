<?php
// classes/Uitbreiding.php
require_once __DIR__ . '/KastItem.php';

class Uitbreiding extends KastItem {
    private ?string $basisspel;

    public function __construct(
        string $titel,
        string $eigenaar,
        string $fotoPad,
        ?string $basisspel = null,
        int $id = 0
    ) {
        // OOK HIER VERPLICHT:
        parent::__construct($titel, $eigenaar, $fotoPad, $id);

        $this->basisspel = $basisspel;
    }

    public function getType(): string {
        return 'Uitbreiding';
    }

    public function omschrijving(): string {
        return !empty($this->basisspel) ? "Uitbreiding op: {$this->basisspel}" : "Uitbreiding";
    }
}