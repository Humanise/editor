<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
 xmlns="http://www.w3.org/1999/xhtml"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/publishing/page/1.0/"
 xmlns:f="http://uri.in2isoft.com/onlinepublisher/publishing/frame/1.0/"
 xmlns:h="http://uri.in2isoft.com/onlinepublisher/publishing/hierarchy/1.0/"
 xmlns:n="http://uri.in2isoft.com/onlinepublisher/class/news/1.0/"
 xmlns:o="http://uri.in2isoft.com/onlinepublisher/class/object/1.0/"
 xmlns:util="http://uri.in2isoft.com/onlinepublisher/util/"
 xmlns:hr="http://uri.in2isoft.com/onlinepublisher/part/horizontalrule/1.0/"
 exclude-result-prefixes="p f h n o util hr"
 >
<xsl:output encoding="UTF-8" method="xml" omit-xml-declaration="yes"/>

<xsl:include href="../../basic/xslt/util.xsl"/>
<xsl:include href="front.xsl"/>

<xsl:template match="p:page">
  <xsl:call-template name="util:doctype"/>
  <html>
    <xsl:call-template name="util:html-attributes"/>
    <xsl:call-template name="head"/>
    <xsl:call-template name="body"/>
  </html>
</xsl:template>

<xsl:template name="head">
  <head>
    <title>
      <xsl:if test="not(//p:page/@id=//p:context/p:home/@page)">
        <xsl:value-of select="@title"/>
        <xsl:text> - </xsl:text>
      </xsl:if>
      <xsl:value-of select="f:frame/@title"/>
    </title>
    <xsl:call-template name="util:viewport"/>
    <xsl:call-template name="util:metatags"/>
    <xsl:call-template name="util:css"/>
    <xsl:call-template name="util:js"/>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+SC:wght@300;400&amp;display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Unna:400%7CLora:400,400italic,700%7CMerriweather:400,700,300,200%7CCinzel:400%7CGloria+Hallelujah"/>
  </head>
</xsl:template>

<xsl:template name="body">
  <body>
    <xsl:call-template name="util:languages"><xsl:with-param name="tag" select="'p'"/></xsl:call-template>
    <xsl:choose>
      <xsl:when test="//p:page/p:context/p:home[@page=//p:page/@id]">
        <xsl:call-template name="front"/>
      </xsl:when>
      <xsl:otherwise>
        <xsl:call-template name="header"/>
        <xsl:call-template name="page"/>
      </xsl:otherwise>
    </xsl:choose>

    <footer class="layout_footer">
      <p><a href="http://www.humanise.dk/" title="Humanise" class="layout_footer_link"><span class="layout_footer_text">Designet og udviklet af Humanise</span></a></p>
    </footer>
    <xsl:call-template name="util:googleanalytics"/>
  </body>
</xsl:template>

<xsl:template name="header">
  <header class="header">
    <a class="header_logo">
      <xsl:attribute name="href">
        <xsl:choose>
          <xsl:when test="//p:page/p:meta/p:language='en'">/en/</xsl:when>
          <xsl:otherwise>/</xsl:otherwise>
        </xsl:choose>
      </xsl:attribute>
      <h1 id="title" class="header_title">Lotte Munk</h1>
      <p id="job" class="header_job">
        <xsl:choose>
          <xsl:when test="//p:page/p:meta/p:language='en'"><xsl:text>Actress</xsl:text></xsl:when>
          <xsl:otherwise><xsl:text>Skuespiller</xsl:text></xsl:otherwise>
        </xsl:choose>
      </p>
    </a>
    <nav class="header_navigation">
      <ul class="header_menu js-menu">
        <xsl:comment/>
        <xsl:apply-templates select="f:frame/h:hierarchy/h:item"/>
      </ul>
    </nav>
  </header>
</xsl:template>

<xsl:template name="page">
  <div class="layout">
    <div class="layout_content">
      <xsl:apply-templates select="p:content"/>
      <xsl:comment/>
    </div>
  </div>
</xsl:template>




<xsl:template match="h:hierarchy/h:item">
  <xsl:if test="not(@hidden='true')">
    <xsl:variable name="style">
      <xsl:choose>
        <xsl:when test="//p:page/@id=@page"><xsl:text>selected</xsl:text></xsl:when>
        <xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text>highlighted</xsl:text></xsl:when>
        <xsl:otherwise>normal</xsl:otherwise>
      </xsl:choose>
    </xsl:variable>
    <li class="header_menu_item header_menu_item-{$style}">
      <a class="header_menu_link header_menu_link-{$style}">
        <xsl:attribute name="data-path"><xsl:value-of select="@path"/></xsl:attribute>
        <xsl:call-template name="util:link"/>
        <xsl:value-of select="@title"/>
      </a>
    </li>
  </xsl:if>
</xsl:template>





</xsl:stylesheet>