<?php
// classes/Bordspel.php
require_once __DIR__ . '/KastItem.php';

class Bordspel extends KastItem {
    private ?int $minSpelers;
    private ?int $maxSpelers;
    private ?int $speelduur;
    private ?string $moeilijkheidsgraad;

    public function __construct(
        string $titel,
        string $eigenaar,
        string $fotoPad,
        ?int $minSpelers = null,
        ?int $maxSpelers = null,
        ?int $speelduur = null,
        ?string $moeilijkheidsgraad = null,
        int $id = 0
    ) {
        parent::__construct($titel, $eigenaar, $fotoPad, $id);

        $this->minSpelers = $minSpelers !== null ? (int)$minSpelers : null;
        $this->maxSpelers = $maxSpelers !== null ? (int)$maxSpelers : null;
        $this->speelduur = $speelduur !== null ? (int)$speelduur : null;
        $this->moeilijkheidsgraad = $moeilijkheidsgraad;
    }

    public function getMinSpelers(): ?int {
        return $this->minSpelers;
    }

    public function getMaxSpelers(): ?int {
        return $this->maxSpelers;
    }

    public function getSpeelduur(): ?int {
        return $this->speelduur;
    }

    public function getMoeilijkheidsgraad(): ?string {
        return $this->moeilijkheidsgraad;
    }

    public function getType(): string {
        return 'Bordspel';
    }

    public function omschrijving(): string {
        $details = [];
        if ($this->minSpelers && $this->maxSpelers) {
            $details[] = "Spelers: {$this->minSpelers}-{$this->maxSpelers}";
        }
        if ($this->speelduur) {
            $details[] = "Duur: {$this->speelduur} min";
        }
        if ($this->moeilijkheidsgraad) {
            $details[] = "Niveau: {$this->moeilijkheidsgraad}";
        }
        return empty($details) ? 'Standaard bordspel' : implode(' | ', $details);
    }

    public function toXmlNode(DOMDocument $dom): DOMElement {
        // Haal de basis-node op met titel, foto en eigenaar
        $itemElem = parent::toXmlNode($dom);

        // Voeg bordspel-specifieke attributen toe
        if ($this->minSpelers !== null && $this->maxSpelers !== null) {
            $itemElem->setAttribute('spelers', "{$this->minSpelers}-{$this->maxSpelers}");
        }
        if ($this->speelduur !== null) {
            $itemElem->setAttribute('speelduur', (string)$this->speelduur);
        }
        if ($this->moeilijkheidsgraad !== null && $this->moeilijkheidsgraad !== '') {
            $itemElem->setAttribute('moeilijkheidsgraad', $this->moeilijkheidsgraad);
        }

        return $itemElem;
    }
    
}