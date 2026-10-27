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
    public function getBasisspel(): string{
        return $this->basisspel;
    }

    public function toXmlNode(DOMDocument $dom): DOMElement {
        $itemElem = $dom->createElement('item');
        $itemElem->setAttribute('type', 'uitbreiding');

        // 1. Titel
        $titel = $dom->createElement('titel');
        $titel->textContent = $this->getTitel();
        $itemElem->appendChild($titel);

        // 2. Basisspel
        if (!empty($this->basisspel)) {
            $basisElem = $dom->createElement('basisspel');
            $basisElem->textContent = $this->basisspel;
            $itemElem->appendChild($basisElem);
        }

        // 3. Foto
        $foto = $dom->createElement('foto');
        $foto->textContent = $this->getFotoPad();
        $itemElem->appendChild($foto);

        // 4. Eigenaar
        $eigenaar = $dom->createElement('eigenaar');
        $eigenaar->textContent = $this->getEigenaar();
        $itemElem->appendChild($eigenaar);

        return $itemElem;
    }

}