<?php
// export.php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$xmlFile = 'avondlijst.xml';
$xsdFile = 'avondlijst.xsd';
$xsltFile = 'avondlijst.xslt';

// 1. XML inladen
$xml = new DOMDocument();
if (!$xml->load($xmlFile)) {
    die("Fout bij het laden van {$xmlFile}.");
}

// 2. Valideren tegen XSD
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

// 3. XSLT inladen
$xsl = new DOMDocument();
if (!$xsl->load($xsltFile)) {
    die("Fout bij het laden van {$xsltFile}.");
}

// 4. Transformeren en printen
$proc = new XSLTProcessor();
$proc->importStyleSheet($xsl);

echo $proc->transformToXML($xml);