<?php
// export.php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

require_once 'config/Database.php';
require_once 'classes/ItemRepository.php';

$db = Database::getConnection();
$itemRepo = new ItemRepository($db);

// 1. Live XML genereren uit de MySQL-database
$xmlString = $itemRepo->exportToXml();

// Schrijf weg naar bestand zodat je fysieke bestand altijd up-to-date is
file_put_contents('avondlijst.xml', $xmlString);

// 2. DOMDocument laden vanuit de gegenereerde XML-string
$xml = new DOMDocument();
$xml->loadXML($xmlString);

// 3. Valideren tegen het XSD-schema
$xsdFile = 'avondlijst.xsd';
libxml_use_internal_errors(true);

if (!$xml->schemaValidate($xsdFile)) {
    echo "<h2>XSD Validatiefouten:</h2><ul>";
    foreach (libxml_get_errors() as $error) {
        echo "<li>" . htmlspecialchars($error->message) . " (regel {$error->line})</li>";
    }
    echo "</ul>";
    libxml_clear_errors();
    exit;
}

// 4. XSLT inladen en transformeren
$xsl = new DOMDocument();
if (!$xsl->load('avondlijst.xslt')) {
    die("Fout bij het inladen van avondlijst.xslt.");
}

$proc = new XSLTProcessor();
$proc->importStyleSheet($xsl);

// 5. HTML tonen in de browser
echo $proc->transformToXML($xml);