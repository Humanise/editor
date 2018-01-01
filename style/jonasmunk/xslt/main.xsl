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
 xmlns:html="http://uri.in2isoft.com/onlinepublisher/publishing/html/1.0/"
 xmlns:widget="http://uri.in2isoft.com/onlinepublisher/part/widget/1.0/"
 exclude-result-prefixes="p f h n o html util"
 >
  <xsl:output encoding="UTF-8" method="xml" omit-xml-declaration="yes"/>

  <xsl:include href="../../basic/xslt/util.xsl"/>

  <xsl:template match="p:page">
    <xsl:variable name="theme" select="//p:design/p:parameter[@key='theme']" />
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
        <xsl:call-template name="util:viewport"/>
        <xsl:call-template name="util:metatags"/>
        <xsl:call-template name="util:js"/>
        <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500|Open+Sans:300italic,400italic,600italic,400,600,500,700,300,200,800" rel="stylesheet"/>
        <xsl:call-template name="util:css"/>
      </head>
      <body>
        <xsl:if test="$theme">
          <xsl:attribute name="class">
            <xsl:value-of select="$theme"/>
          </xsl:attribute>
        </xsl:if>
            <xsl:choose>
              <xsl:when test="$theme='vitae'">
                <div class="vitae_hero">
                  <div class="vitae_hero_body"><xsl:comment/></div>
                </div>
                <div class="vitae_hero_text">Jonas Munk</div>
                <div class="vitae_hero_sub">Curriculum Vitae</div>
                <div class="vitae_layout">
                  <div class="vitae_content">
                    <xsl:call-template name="vitae-menu"/>
                    <xsl:apply-templates select="p:content"/>
                    <xsl:comment/>
                  </div>
                  <div class="vitae_footer">
                    <a href="http://www.humanise.dk/" class="vitae_designed">Designet og udviklet af Humanise</a>
                  </div>
                </div>
              </xsl:when>
              <xsl:otherwise>
                <div class="layout">
                  <div class="header">
                    <a class="header_logo">
                      <xsl:attribute name="href">
                        <xsl:choose>
                          <xsl:when test="//p:context/p:home/@path"><xsl:value-of select="//p:context/p:home/@path"/></xsl:when>
                          <xsl:when test="//p:context/p:home/@page">?id=<xsl:value-of select="//p:context/p:home/@page"/></xsl:when>
                        </xsl:choose>
                      </xsl:attribute>
                      <xsl:text>Jonas Munk</xsl:text>
                    </a>
                  <xsl:call-template name="navigation-first-level"/>
                  </div>
                  <div class="layout_content">
                    <xsl:call-template name="navigation-second-level"/>
                    <xsl:apply-templates select="p:content"/>
                    <xsl:comment/>
                  </div>
                  <div class="layout_footer">
                    <a href="http://www.humanise.dk/" class="layout_designed"><span class="layout_designed_text">Designet og udviklet af Humanise</span></a>
                  </div>
                </div>
              </xsl:otherwise>
            </xsl:choose>
        <xsl:call-template name="util:googleanalytics"/>
      </body>
    </html>
  </xsl:template>

  <xsl:template name="navigation-first-level">
    <xsl:if test="//f:frame/h:hierarchy/h:item[not(@hidden='true')]">
      <ul class="header_menu">
        <xsl:for-each select="//f:frame/h:hierarchy/h:item">
          <xsl:if test="not(@hidden='true')">
            <li class="header_menu_item">
              <a>
                <xsl:attribute name="class">
                  <xsl:text>header_menu_link</xsl:text>
                  <xsl:choose>
                    <xsl:when test="//p:page/@id=@page"><xsl:text> is-selected</xsl:text></xsl:when>
                    <xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text> is-active</xsl:text></xsl:when>
                  </xsl:choose>
                </xsl:attribute>
                <xsl:call-template name="util:link"/>
                <xsl:value-of select="@title"/>
              </a>
            </li>
          </xsl:if>
        </xsl:for-each>
      </ul>
    </xsl:if>
  </xsl:template>

  <xsl:template name="vitae-menu">
    <xsl:if test="//f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item[not(@hidden='true')]">
      <ul class="vitae_menu">
        <xsl:for-each select="//f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item">
          <xsl:if test="not(@hidden='true')">
            <li class="vitae_menu_item">
              <a>
                <xsl:attribute name="class">
                  <xsl:text>vitae_menu_link</xsl:text>
                  <xsl:choose>
                    <xsl:when test="//p:page/@id=@page"><xsl:text> is-selected</xsl:text></xsl:when>
                    <xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text> is-active</xsl:text></xsl:when>
                  </xsl:choose>
                </xsl:attribute>
                <xsl:call-template name="util:link"/>
                <xsl:value-of select="@title"/>
              </a>
            </li>
          </xsl:if>
        </xsl:for-each>
      </ul>
    </xsl:if>
  </xsl:template>

  <xsl:template name="navigation-second-level">
    <xsl:if test="//f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item[not(@hidden='true')]">
      <ul class="submenu">
        <xsl:for-each select="//f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item">
          <xsl:if test="not(@hidden='true')">
            <li class="submenu_item">
              <a>
                <xsl:attribute name="class">
                  <xsl:text>submenu_link</xsl:text>
                  <xsl:choose>
                    <xsl:when test="//p:page/@id=@page"><xsl:text> is-selected</xsl:text></xsl:when>
                    <xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text> is-active</xsl:text></xsl:when>
                  </xsl:choose>
                </xsl:attribute>
                <xsl:call-template name="util:link"/>
                <xsl:value-of select="@title"/>
              </a>
            </li>
          </xsl:if>
        </xsl:for-each>
      </ul>
    </xsl:if>
  </xsl:template>

  <xsl:template match="widget:bio">
    <div class="bio">
      <xsl:for-each select="widget:event">
        <div class="bio_event" data-build-in="pop">
          <span class="bio_time">
            <xsl:value-of select="@from"/>
            <span class="bio_time_divider"> &#8594; </span>
            <xsl:value-of select="@to"/>
          </span>
          <div class="bio_content">
            <strong class="bio_title"><xsl:value-of select="widget:title"/></strong>
            <xsl:for-each select="widget:place">
              <xsl:text>, </xsl:text>
              <a class="bio_place common_link" href="{@url}"><span class="common_link_text"><xsl:value-of select="."/></span></a>
            </xsl:for-each>
            <p class="bio_text"><xsl:value-of select="widget:text"/></p>
            <xsl:if test="widget:link or widget:point">
              <ol class="bio_points">
                <xsl:for-each select="widget:point">
                  <li class="bio_point"><strong><xsl:value-of select="@prefix"/>: </strong><xsl:value-of select="."/></li>
                </xsl:for-each>
                <xsl:if test="widget:link">
                  <li class="bio_point">
                    <xsl:for-each select="widget:link">
                      <a href="{@href}" class="common_link"><span class="common_link_text"><xsl:value-of select="."/></span></a><xsl:text> </xsl:text>
                    </xsl:for-each>
                  </li>
                </xsl:if>
              </ol>
            </xsl:if>
          </div>
        </div>
      </xsl:for-each>
    </div>
  </xsl:template>

</xsl:stylesheet>