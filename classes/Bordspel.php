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
        // DIT IS DE CRUCIALE REGEL:
        parent::__construct($titel, $eigenaar, $fotoPad, $id);

        $this->minSpelers = $minSpelers;
        $this->maxSpelers = $maxSpelers;
        $this->speelduur = $speelduur;
        $this->moeilijkheidsgraad = $moeilijkheidsgraad;
    }

    public function getType(): string {
        return 'Bordspel';
    }
    public function getMinSpelers(): int{
        return "minSpelers";
    }
    public function getMaxSpelers(): int{
        return "maxSpelers";
    }
    public function getSpeelduur(): int{
        return "speelduur";
    }
    public function getMoeilijkheidsgraad(): string{
        return "moeilijkheidsgraad";
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
}