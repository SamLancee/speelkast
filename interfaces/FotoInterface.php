<?php
// interfaces/FotoInterface.php

interface FotoInterface {
    public function checkFileName(string $bestandsnaam): bool;
    public function showImage(): string;
}