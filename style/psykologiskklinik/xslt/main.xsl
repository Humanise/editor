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


<xsl:template match="p:page">
<xsl:call-template name="util:doctype"/>
<html>
  <xsl:call-template name="util:html-attributes"/>
<head>
  <title>
    <xsl:if test="not(//p:page/@id=//p:context/p:home/@page)">
      <xsl:value-of select="@title"/>
      <xsl:text> - </xsl:text>
    </xsl:if>
    <xsl:value-of select="f:frame/@title"/>
  </title>
  <link href="https://fonts.googleapis.com/css?family=Ovo" rel="stylesheet"/>
  <xsl:call-template name="util:viewport"/>
  <xsl:call-template name="util:metatags"/>
  <xsl:call-template name="util:css"/>
  <xsl:call-template name="util:js"/>
</head>
<body>
  <div class="layout">
    <div class="layout_top">
      <div class="header">
        <p class="header_logo">Psykologisk Klinik ved Kenni Graversen - Cand. psych. aut. - Specialist i psykoterapi</p>
        <div class="header_menu">
          <ul class="header_menu_items">
            <xsl:comment/>
            <xsl:apply-templates select="f:frame/h:hierarchy/h:item"/>
          </ul>
        </div>
      </div>
    </div>
    <div class="layout_middle">
      <div class="layout_content">
        <xsl:apply-templates select="p:content"/>
        <xsl:comment/>
      </div>
    </div>
    <div class="layout_bottom">
      <div class="layout_bottom_effect"><xsl:comment/></div>
      <p class="layout_humanise">
        <a href="https://www.humanise.dk/" title="Humanise" class="layout_humanise_link">
          Designet og udviklet af Humanise
        </a>
      </p>
    </div>
  </div>
  <xsl:call-template name="util:googleanalytics"/>
</body>
</html>
</xsl:template>


<xsl:template match="p:content">
  <xsl:apply-templates/>
</xsl:template>




<xsl:template match="h:hierarchy/h:item">
  <xsl:if test="not(@hidden='true')">
  <xsl:variable name="style">
  <xsl:choose>
    <xsl:when test="//p:page/@id=@page">
      <xsl:text>is-selected</xsl:text>
    </xsl:when>
    <xsl:when test="descendant-or-self::*/@page=//p:page/@id">
      <xsl:text>is-highlighted</xsl:text>
    </xsl:when>
  </xsl:choose>
  </xsl:variable>
  <li class="header_menu_item">
    <a class="header_menu_link {$style}">
      <xsl:call-template name="util:link"/>
      <xsl:value-of select="@title"/>
    </a>
  </li>
  </xsl:if>
</xsl:template>





<!--            Links              -->


<xsl:template match="f:links/f:bottom">
  <div class="case_links">
  <xsl:apply-templates/>
  <xsl:if test="f:link"><span>&#160;&#183;&#160;</span></xsl:if>
  <a title="XHTML 1.1" class="common" href="http://validator.w3.org/check?uri=referer"><span>XHTML 1.1</span></a>
  </div>
</xsl:template>

<xsl:template match="f:links/f:bottom/f:link">
  <xsl:if test="position()>1"><span>&#160;&#183;&#160;</span></xsl:if>
  <a title="{@alternative}" class="common">
  <xsl:call-template name="util:link"/>
  <span><xsl:value-of select="@title"/></span>
  </a>
</xsl:template>


<!--            Text              -->

<xsl:template match="f:text/f:bottom">
  <span class="text">
    <xsl:comment/>
    <xsl:apply-templates/>
  </span>
</xsl:template>

<xsl:template match="f:text/f:bottom/f:break">
  <br/>
</xsl:template>

<xsl:template match="f:text/f:bottom/f:link">
  <a title="{@alternative}" class="common">
    <xsl:call-template name="util:link"/>
    <span><xsl:apply-templates/></span>
  </a>
</xsl:template>

</xsl:stylesheet>