<?php
// export.php (Controller)
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

require_once 'config/Database.php';
require_once 'classes/ItemRepository.php';
require_once 'classes/AvondlijstExport.php';

$db = Database::getConnection();
$itemRepo = new ItemRepository($db);
$exporter = new AvondlijstExporter('avondlijst.xsd', 'avondlijst.xslt');

// 1. Data ophalen en wegschrijven via models
$xmlString = $itemRepo->exportToXml();
file_put_contents('avondlijst.xml', $xmlString);

// 2. Valideren via exporter-model
$xsdErrors = $exporter->validateXml($xmlString);

if (!empty($xsdErrors)) {
    // Laad de fouten-view in plaats van ruwe echo's
    require_once 'views/export_error_view.php';
    exit;
}

// 3. Transformeren naar HTML en tonen via view
$renderedHtml = $exporter->transformToHtml($xmlString);
require_once 'views/export_view.php';