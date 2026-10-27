<?php
// classes/AvondlijstExporter.php

class AvondlijstExporter {
    private string $xsdFile;
    private string $xsltFile;

    public function __construct(string $xsdFile = 'avondlijst.xsd', string $xsltFile = 'avondlijst.xslt') {
        $this->xsdFile = $xsdFile;
        $this->xsltFile = $xsltFile;
    }

    /**
     * Valideert een XML-string tegen het XSD-schema.
     * Geeft een array met foutmeldingen terug (leeg = valide).
     */
    public function validateXml(string $xmlString): array {
        $xml = new DOMDocument();
        $xml->loadXML($xmlString);

        libxml_use_internal_errors(true);
        $errors = [];

        if (!$xml->schemaValidate($this->xsdFile)) {
            foreach (libxml_get_errors() as $error) {
                $errors[] = trim($error->message) . " (regel {$error->line})";
            }
            libxml_clear_errors();
        }

        return $errors;
    }

    /**
     * Transformeert een XML-string met het XSLT-bestand naar HTML.
     */
    public function transformToHtml(string $xmlString): string {
        $xml = new DOMDocument();
        $xml->loadXML($xmlString);

        $xsl = new DOMDocument();
        if (!$xsl->load($this->xsltFile)) {
            throw new Exception("Kon {$this->xsltFile} niet inladen.");
        }

        $proc = new XSLTProcessor();
        $proc->importStyleSheet($xsl);

        $html = $proc->transformToXML($xml);
        if ($html === false) {
            throw new Exception("Transformatie via XSLT is mislukt.");
        }

        return $html;
    }
}