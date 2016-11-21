<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
 xmlns="http://www.w3.org/1999/xhtml"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:h="http://uri.in2isoft.com/onlinepublisher/part/header/1.0/"
 xmlns:util="http://uri.in2isoft.com/onlinepublisher/util/"
 xmlns:style="http://uri.in2isoft.com/onlinepublisher/style/1.0/"
 exclude-result-prefixes="h util style"
 >

  <xsl:template match="h:header">
    <xsl:variable name="id" select="concat('part_header-', generate-id())" />
    <xsl:variable name="level">
      <xsl:choose>
        <xsl:when test="@level='1' or @level='2' or @level='3' or @level='4' or @level='5' or @level='6'">
          <xsl:value-of select="@level"/>
        </xsl:when>
        <xsl:otherwise>
          <xsl:text>1</xsl:text>
        </xsl:otherwise>
      </xsl:choose>
    </xsl:variable>
    <xsl:element name="{concat('h',$level)}">
      <xsl:attribute name="class">
        <xsl:text>part_header common common_header</xsl:text>
        <xsl:value-of select="concat(' part_header-', $level, ' common_header-', $level)"/>
        <xsl:if test="../../style:style/style:if">
          <xsl:value-of select="concat(' ', $id)"/>
        </xsl:if>
      </xsl:attribute>
      <xsl:apply-templates/>
      <xsl:comment/>
    </xsl:element>

    <xsl:if test="../../style:style/style:if">
    <style>
      <xsl:for-each select="../../style:style/style:if">
        <xsl:call-template name="util:media-before"/>
        <xsl:for-each select="style:component[@name='base']">
          <xsl:value-of select="concat('.', $id, '{')"/>
            <xsl:call-template name="util:rules"/>
          <xsl:text>}</xsl:text>
        </xsl:for-each>
        <xsl:call-template name="util:media-after"/>
      </xsl:for-each>
    </style>
    </xsl:if>
  </xsl:template>

	<xsl:template match="h:style">
		<xsl:attribute name="style">
			<xsl:if test="@font-size">font-size: <xsl:value-of select="@font-size"/>;</xsl:if>
			<xsl:if test="@font-family">font-family: <xsl:value-of select="@font-family"/>;</xsl:if>
			<xsl:if test="@font-style">font-style: <xsl:value-of select="@font-style"/>;</xsl:if>
			<xsl:if test="@font-weight">font-weight: <xsl:value-of select="@font-weight"/>;</xsl:if>
			<xsl:if test="@color">color: <xsl:value-of select="@color"/>;</xsl:if>
			<xsl:if test="@line-height">line-height: <xsl:value-of select="@line-height"/>;</xsl:if>
			<xsl:if test="@text-align">text-align: <xsl:value-of select="@text-align"/>;</xsl:if>
			<xsl:if test="@word-spacing">word-spacing: <xsl:value-of select="@word-spacing"/>;</xsl:if>
			<xsl:if test="@letter-spacing">letter-spacing: <xsl:value-of select="@letter-spacing"/>;</xsl:if>
			<xsl:if test="@text-indent">text-indent: <xsl:value-of select="@text-indent"/>;</xsl:if>
			<xsl:if test="@text-transform">text-transform: <xsl:value-of select="@text-transform"/>;</xsl:if>
			<xsl:if test="@font-variant">font-variant: <xsl:value-of select="@font-variant"/>;</xsl:if>
			<xsl:if test="@text-decoration">text-decoration: <xsl:value-of select="@text-decoration"/>;</xsl:if>
		</xsl:attribute>
	</xsl:template>

	<xsl:template match="h:break">
		<br/>
	</xsl:template>

	<xsl:template match="h:strong">
		<strong>
			<xsl:apply-templates/>
		</strong>
	</xsl:template>

	<xsl:template match="h:em">
		<em>
			<xsl:apply-templates/>
		</em>
	</xsl:template>

	<xsl:template match="h:del">
		<del>
			<xsl:apply-templates/>
		</del>
	</xsl:template>

	<xsl:template match="h:link">
		<a class="common common_link">
			<xsl:call-template name="util:link"/>
			<span class="common_link_text"><xsl:apply-templates/></span>
		</a>
	</xsl:template>

</xsl:stylesheet>