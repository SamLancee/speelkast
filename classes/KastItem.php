<?php
require_once __DIR__ . '/../interfaces/FotoInterface.php';

abstract class KastItem implements FotoInterface {
    protected int $id;
    protected string $titel;
    protected string $eigenaar;
    protected string $fotoPad;

    public function __construct(string $titel, string $eigenaar, string $fotoPad, int $id =0){
        $this->titel = $titel;
        $this->eigenaar = $eigenaar;
        $this->fotoPad= $fotoPad;
        $this->id= $id;
    }
    public function getTitel(): string{
        return $this->titel;
    }
    public function getEigenaar(): string{
        return $this->eigenaar;
    }
    public function getFotoPad(): string{
        return $this->fotoPad;
    }
    public function checkFileName(string $bestandsnaam): bool {
        $ext = strtolower(pathinfo($bestandsnaam, PATHINFO_EXTENSION));
        return in_array($ext, ['jpg', 'jpeg', 'png', 'gif'], true);
    }

    public function showImage(): string {
        $pad = htmlspecialchars($this->fotoPad);
        $titel = htmlspecialchars($this->titel);
        return "<img src='{$pad}' alt='{$titel}' style='max-width: 140px; height: auto; border-radius: 4px;'>";
    }

    abstract public function omschrijving(): string;
    abstract public function getType(): string;

}