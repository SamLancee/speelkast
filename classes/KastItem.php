<?php
require_once __DIR__ . '/../interfaces/FotoInterface.php';

abstract class KastItem implements FotoInterface {
    protected int $id;
    protected string $titel;
    protected string $eigenaar;
    protected string $fotoPad;

    public function __construct(string $titel, string $eigenaar, string $fotoPad, int $id = 0) {
        $this->titel = $titel;
        $this->eigenaar = $eigenaar;
        $this->fotoPad = $fotoPad;
        $this->id = $id;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getTitel(): string {
        return $this->titel;
    }

    public function getEigenaar(): string {
        return $this->eigenaar;
    }

    public function getFotoPad(): string {
        return $this->fotoPad;
    }

    public function checkFileName(?string $bestandsnaam = null): bool {
        $pad = $bestandsnaam ?? $this->fotoPad;

        if (empty($pad)) {
            return false;
        }

        // Controleer op toegestane extensie
        $ext = strtolower(pathinfo($pad, PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif'], true)) {
            return false;
        }

        return file_exists($pad);
    }

    public function showImage(): string {
        if (!$this->checkFileName()) {
            return "<span class='no-image'>Geen geldige afbeelding gevonden</span>";
        }

        $pad = htmlspecialchars($this->fotoPad);
        $titel = htmlspecialchars($this->titel);
        return "<img src='{$pad}' alt='{$titel}' style='max-width: 140px; height: auto; border-radius: 4px;'>";
    }

    abstract public function omschrijving(): string;
    abstract public function getType(): string;

    /**
     * Zet het item om naar een DOMElement voor het XML-exportbestand.
     */
    public function toXmlNode(DOMDocument $dom): DOMElement {
        $itemElem = $dom->createElement('item');
        $itemElem->setAttribute('type', strtolower($this->getType()));

        $titel = $dom->createElement('titel');
        $titel->textContent = $this->getTitel();
        $itemElem->appendChild($titel);

        $foto = $dom->createElement('foto');
        $foto->textContent = $this->getFotoPad();
        $itemElem->appendChild($foto);

        $eigenaar = $dom->createElement('eigenaar');
        $eigenaar->textContent = $this->getEigenaar();
        $itemElem->appendChild($eigenaar);

        return $itemElem;
    }
}