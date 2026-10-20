<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" encoding="UTF-8" indent="yes" />

    <xsl:template match="/speelkast">
        <html lang="nl">
        <head>
            <meta charset="UTF-8" />
            <title>Avondlijst Spellenavond</title>
            <style>
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 15px;
                }
                th, td {
                    border: 1px solid #ccc;
                    padding: 8px 12px;
                    text-align: left;
                    vertical-align: middle;
                }
                th {
                    background-color: #f2f2f2;
                }
                tr:nth-child(even) {
                    background-color: #fafafa;
                }
                .thumb {
                    max-width: 70px;
                    height: auto;
                    display: block;
                }
                .badge {
                    text-transform: capitalize;
                    font-weight: bold;
                }
            </style>
        </head>
        <body>
            <p><a href="index.php">&#8592; Terug naar de kast</a></p>
            <h1>Avondlijst - Spellenavond</h1>
            <p>Overzicht gegenereerd via XML en XSLT.</p>

            <table>
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Titel</th>
                        <th>Type</th>
                        <th>Spelers</th>
                        <th>Speelduur</th>
                        <th>Moeilijkheidsgraad</th>
                        <th>Basisspel</th>
                        <th>Eigenaar</th>
                    </tr>
                </thead>
                <tbody>
                    <xsl:for-each select="item">
                        <tr>
                            <!-- 1. Foto -->
                            <td>
                                <img>
                                    <xsl:attribute name="src">
                                        <xsl:value-of select="foto" />
                                    </xsl:attribute>
                                    <xsl:attribute name="alt">
                                        <xsl:value-of select="titel" />
                                    </xsl:attribute>
                                    <xsl:attribute name="class">thumb</xsl:attribute>
                                </img>
                            </td>

                            <!-- 2. Titel -->
                            <td>
                                <strong><xsl:value-of select="titel" /></strong>
                            </td>

                            <!-- 3. Type (attribuut) -->
                            <td>
                                <span class="badge"><xsl:value-of select="@type" /></span>
                            </td>

                            <!-- 4. Spelers (attribuut) -->
                            <td>
                                <xsl:choose>
                                    <xsl:when test="@spelers">
                                        <xsl:value-of select="@spelers" />
                                    </xsl:when>
                                    <xsl:otherwise>-</xsl:otherwise>
                                </xsl:choose>
                            </td>

                            <!-- 5. Speelduur (attribuut) -->
                            <td>
                                <xsl:choose>
                                    <xsl:when test="@speelduur">
                                        <xsl:value-of select="@speelduur" /> min
                                    </xsl:when>
                                    <xsl:otherwise>-</xsl:otherwise>
                                </xsl:choose>
                            </td>

                            <!-- 6. Moeilijkheidsgraad (attribuut) -->
                            <td>
                                <xsl:choose>
                                    <xsl:when test="@moeilijkheidsgraad">
                                        <xsl:value-of select="@moeilijkheidsgraad" />
                                    </xsl:when>
                                    <xsl:otherwise>-</xsl:otherwise>
                                </xsl:choose>
                            </td>

                            <!-- 7. Basisspel (element) -->
                            <td>
                                <xsl:choose>
                                    <xsl:when test="basisspel">
                                        <xsl:value-of select="basisspel" />
                                    </xsl:when>
                                    <xsl:otherwise>-</xsl:otherwise>
                                </xsl:choose>
                            </td>

                            <!-- 8. Eigenaar (element) -->
                            <td>
                                <xsl:value-of select="eigenaar" />
                            </td>
                        </tr>
                    </xsl:for-each>
                </tbody>
            </table>
        </body>
        </html>
    </xsl:template>

</xsl:stylesheet>