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
 xmlns:widget="http://uri.in2isoft.com/onlinepublisher/part/widget/1.0/"
 exclude-result-prefixes="p f h n o util hr widget"
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
    <meta name="format-detection" content="telephone=no"/>
    <xsl:call-template name="util:viewport"/>
    <xsl:call-template name="util:metatags"/>
    <xsl:call-template name="util:js"/>
    <xsl:call-template name="util:load-font">
      <xsl:with-param name="href" select="'http://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic,900'"/>
      <xsl:with-param name="family" select="'Lato'"/>
      <xsl:with-param name="class" select="'font_lato'"/>
    </xsl:call-template>
    <xsl:call-template name="util:load-font">
      <xsl:with-param name="href" select="'http://fonts.googleapis.com/css?family=Lora:400italic'"/>
      <xsl:with-param name="family" select="'Lora'"/>
      <xsl:with-param name="class" select="'font_lora'"/>
    </xsl:call-template>
    <xsl:call-template name="util:css">
      <xsl:with-param name="inline" select="'true'"/>
      <xsl:with-param name="ie-lt-9" select="'true'"/>
    </xsl:call-template>
  </head>
  <body>
    <div class="layout">
      <xsl:call-template name="header"/>
      <main class="layout_middle">
        <xsl:apply-templates select="p:content"/>
        <xsl:call-template name="util:userstatus"/>
      </main>
      <footer class="layout_bottom">
        <p><a href="http://www.humanise.dk/" class="layout_humanise" title="Humanise">Designet og udviklet af Humanise</a></p>
      </footer>
    </div>
    <xsl:call-template name="util:googleanalytics"/>
  </body>
</html>
</xsl:template>

<xsl:template name="header">
  <header class="header">
    <p class="header_logo">Dalleruphallerne</p>
    <h1 class="header_title">Opbevaring af campingvogne og lagerhaller</h1>
  </header>
</xsl:template>

<xsl:template match="p:content">
  <div class="layout_content">
    <xsl:apply-templates/>
    <xsl:comment/>
  </div>
</xsl:template>

<xsl:template match="widget:language">
  <div class="language">
    <xsl:if test="$language!='en' and //p:page/p:context/p:translation[@language='en']">
      <a href="{//p:page/p:context/p:translation[@language='en']/@path}" class="language_link language_link-english">English</a>
    </xsl:if>
    <xsl:if test="$language!='da' and //p:page/p:context/p:translation[@language='da']">
      <xsl:text> </xsl:text>
      <a href="{//p:page/p:context/p:translation[@language='da']/@path}" class="language_link language_link-danish">Dansk</a>
    </xsl:if>
    <xsl:if test="$language!='de' and //p:page/p:context/p:translation[@language='de']">
      <xsl:text> </xsl:text>
      <a href="{//p:page/p:context/p:translation[@language='de']/@path}" class="language_link language_link-german">Deutch</a>
    </xsl:if>
  </div>
</xsl:template>

<xsl:template match="widget:offer">
  <div class="offer js-offer">
    <div class="offer_box">
      <h2 class="offer_title"><xsl:value-of select="widget:title"/></h2>
      <p class="offer_name"><xsl:value-of select="widget:name"/>:</p>
      <p class="offer_phone"><a href="tel:{widget:phone}"><xsl:value-of select="widget:phone/@display"/></a></p>
      <p class="offer_mail"><a href="mailto:{widget:mail}"><xsl:value-of select="widget:mail"/></a></p>
      <p class="offer_msg"><a href="sms:{widget:phone}">Send SMS</a></p>
    </div>
  </div>
  <script>
    hui.on(function() {
      if (navigator.userAgent.match(/iphone|ipad|android/gi)) {
        hui.cls.add(hui.find('.js-offer'),'is-message-capable')
      }
    })
  </script>
</xsl:template>

<xsl:template match="widget:prices">
  <div class="prices">
    <div class="prices_body">
    <h2 class="prices_header common_header common_header-2"><xsl:value-of select="widget:header"/><strong>?</strong></h2>
    <xsl:for-each select="widget:product">
      <div class="prices_item">
        <h3 class="prices_title"><xsl:value-of select="widget:title"/></h3>
        <p class="prices_text"><xsl:value-of select="widget:text"/></p>
        <xsl:if test="widget:price and not(widget:price/@text)">
          <p class="prices_price">kr. <strong><xsl:value-of select="widget:price"/></strong>,–</p>
        </xsl:if>
        <xsl:if test="widget:price/@text">
          <p class="prices_price prices_price-text"><xsl:value-of select="widget:price/@text"/></p>
        </xsl:if>
      </div>
    </xsl:for-each>
    </div>
    <p style="text-align: center; margin-top: 25px;"><strong>Bemærk:</strong> Autocampere opbevares ikke.</p>
  </div>
</xsl:template>

<xsl:template match="widget:contact">
  <div class="contact">
    <p class="contact_phone"><span class="contact_phone_prefix"><xsl:value-of select="widget:phone/@prefix"/></span><xsl:text> </xsl:text>
      <a href="tel:{widget:phone/@prefix}{widget:phone}" class="contact_phone_link"><xsl:value-of select="widget:phone"/></a></p>
    <p class="contact_mail"><xsl:value-of select="widget:email/@prefix"/>: <a href="mailto:{widget:email}" class="contact_mail_link"><xsl:value-of select="widget:email"/></a></p>
  </div>
</xsl:template>


<xsl:template match="widget:poster">
  <div class="poster poster-{@variant}">
    <div class="poster_box poster_box-{@variant}"><xsl:value-of select="widget:quote"/><xsl:comment/></div>
  </div>
</xsl:template>


<!-- mobilepay://send?phone=004579909 -->
<xsl:template match="widget:mobile-pay">
  <div class="mobilepay">
    <div class="mobilepay_qr"><xsl:comment/></div>
    <p class="mobilepay_number">Nr: <a href="https://mobilepay.dk/da-dk/pages/betal.aspx?phone=004579909">79 909</a></p>
    <a class="mobilepay_button" href="https://mobilepay.dk/da-dk/pages/betal.aspx?phone=004579909">Betal med MobilePay</a>
  </div>
</xsl:template>

<xsl:template match="widget:quote">
  <blockquote class="quote">
    <div class="quote_body">
      <p class="quote_text"><xsl:apply-templates select="widget:text"/> <span class="quote_name">– <xsl:value-of select="widget:name"/></span></p>
    </div>
  </blockquote>
</xsl:template>

<xsl:template match="widget:quote/widget:text/widget:strong">
  <strong><xsl:apply-templates/></strong>
</xsl:template>

<xsl:template match="widget:warning">
  <p class="warning">
    <xsl:value-of select="."/>
    <xsl:comment/>
  </p>
</xsl:template>

</xsl:stylesheet>