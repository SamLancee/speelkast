<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" encoding="UTF-8" indent="yes" />

    <xsl:template match="/speelkast">
        <html lang="nl">
        <head>
            <meta charset="UTF-8" />
            <title>Avondlijst Spellenavond</title>
        </head>
        <body>
            <p><a href="index.php">&#8592; Terug naar de kast</a></p>
            <h1>Avondlijst - Spellenavond</h1>
            <p>Overzicht gegenereerd via XML en XSLT.</p>
            <hr />

            <xsl:for-each select="item">
                <div>
                    <img>
                        <xsl:attribute name="src">
                            <xsl:value-of select="foto" />
                        </xsl:attribute>
                        <xsl:attribute name="alt">
                            <xsl:value-of select="titel" />
                        </xsl:attribute>
                        <xsl:attribute name="style">max-width: 120px; height: auto;</xsl:attribute>
                    </img>
                    <br />
                    <strong>Titel: </strong> <xsl:value-of select="titel" /><br />
                    <strong>Type: </strong> <xsl:value-of select="@type" /><br />
                    <strong>Eigenaar: </strong> <xsl:value-of select="eigenaar" /><br />

                    <!-- Alleen tonen als speelduur en spelers bestaan -->
                    <xsl:if test="@speelduur">
                        <strong>Speelduur: </strong> <xsl:value-of select="@speelduur" /> min<br />
                    </xsl:if>
                    <xsl:if test="@spelers">
                        <strong>Spelers: </strong> <xsl:value-of select="@spelers" /><br />
                    </xsl:if>
                    <xsl:if test="@moeilijkheidsgraad">
                        <strong>Niveau: </strong> <xsl:value-of select="@moeilijkheidsgraad" /><br />
                    </xsl:if>

                    <!-- Basisspel alleen tonen bij een uitbreiding -->
                    <xsl:if test="basisspel">
                        <strong>Hoort bij: </strong> <xsl:value-of select="basisspel" /><br />
                    </xsl:if>
                </div>
                <hr />
            </xsl:for-each>
        </body>
        </html>
    </xsl:template>

</xsl:stylesheet>