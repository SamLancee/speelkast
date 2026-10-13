<?php
require_once __DIR__."/interfaces/FotoInterface.php";

abstract class KastItem implements FotoInterface {
    protected int $id;
    protected string $titel;
    protected string $eigenaar;
    protected string $fotoPad;

    public function __construct(string $titel, string $eigenaar, string $fotoPad, int $id =0){
        this->title = $titel;
        this->eigenaar = $eigenaar;
        this->fotoPad= $fotoPad;
        this->id= $id;
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

}