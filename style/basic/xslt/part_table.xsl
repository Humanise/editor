<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:t="http://uri.in2isoft.com/onlinepublisher/part/table/1.0/"
 exclude-result-prefixes="t"
 >

	<xsl:template match="t:table">
		<div class="part_table common_font">
		<xsl:choose>
			<xsl:when test="@valid='false'">
				<xsl:value-of select="." disable-output-escaping = "yes"/>
			</xsl:when>
			<xsl:otherwise>
				<xsl:apply-templates mode="copy-no-ns" select="node()"/>
				<xsl:comment/>				
			</xsl:otherwise>
		</xsl:choose>
		</div>
	</xsl:template>
	
	<xsl:template mode="copy-no-ns" match="*">
		<xsl:element name="{name(.)}">
			<xsl:copy-of select="@*"/>
			<xsl:apply-templates mode="copy-no-ns"/>
		</xsl:element>
	</xsl:template>

</xsl:stylesheet>