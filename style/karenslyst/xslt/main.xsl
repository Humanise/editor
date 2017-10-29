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
      <xsl:call-template name="util:viewport"/>
      <xsl:call-template name="util:metatags"/>
      <meta property="og:image" content="{$absolute-path}{$timestamp-url}style/karenslyst/gfx/front.jpg" />
      <xsl:if test="//p:page/@id=//p:context/p:home/@page">
        <xsl:call-template name="util:inline-css">
          <xsl:with-param name="file" select="'style/karenslyst/css/inline_front.css'"/>
        </xsl:call-template>
      </xsl:if>
    <xsl:call-template name="util:js"/>

    <xsl:call-template name="util:load-font">
      <xsl:with-param name="href" select="'http://fonts.googleapis.com/css?family=Playfair+Display:400,400italic'"/>
      <xsl:with-param name="family" select="'Playfair Display'"/>
      <xsl:with-param name="class" select="'font_playfair'"/>
    </xsl:call-template>

    <xsl:call-template name="util:load-font">
      <xsl:with-param name="href" select="'http://fonts.googleapis.com/css?family=Annie+Use+Your+Telescope'"/>
      <xsl:with-param name="family" select="'Annie Use Your Telescope'"/>
      <xsl:with-param name="class" select="'font_annie'"/>
    </xsl:call-template>

    <xsl:call-template name="util:css">
      <xsl:with-param name="inline" select="'true'"/>
      <xsl:with-param name="ie-7" select="'true'"/>
    </xsl:call-template>
  </head>
    <body>
      <div class="layout">
        <xsl:choose>
          <xsl:when test="//p:page/@id=//p:context/p:home/@page">
            <header class="layout_top">
              <h1 class="title">Karenslyst <span class="title_more"> ~ et landsted til leje</span></h1>
              <div class="layout_top_body"><div><xsl:comment/></div></div>
            </header>
            <script type="text/javascript">
              hui.onReady(function() {
                var img = new Image();
                img.onload = function() {
                  var x = hui.get.byClass(document.body,'layout_top_body')[0];
                  hui.cls.add(x,'layout_top_body-loaded');
                }
                img.src = '<xsl:value-of select="$path"/><xsl:value-of select="$timestamp-url"/>style/karenslyst/gfx/front.jpg';
              });
            </script>
          </xsl:when>
          <xsl:otherwise>
          <header class="layout_sub_top">
            <h1 class="title_sub">Karenslyst <span class="title_sub_more"> ~ et landsted til leje</span></h1>
          </header>
          </xsl:otherwise>
        </xsl:choose>

        <nav class="menu">
          <ul class="menu_items">
            <xsl:comment/>
            <xsl:for-each select="//f:frame/h:hierarchy/h:item[not(@hidden='true')]">
              <li>
                <xsl:attribute name="class">
                  <xsl:text>menu_item</xsl:text>
                  <xsl:choose>
                    <xsl:when test="//p:page/@id=@page"> menu_item_selected</xsl:when>
                    <xsl:when test="descendant-or-self::*/@page=//p:page/@id"> menu_item_highlighted</xsl:when>
                  </xsl:choose>
                </xsl:attribute>
                <a class="menu_link">
                  <xsl:call-template name="util:link"/>
                  <span><xsl:value-of select="@title"/></span>
                </a>
              </li>
            </xsl:for-each>
          </ul>
        </nav>
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

<xsl:template match="p:content">
  <div class="layout_content">
    <xsl:apply-templates/>
    <xsl:comment/>
  </div>
</xsl:template>

<xsl:template match="widget:poster">
  <div class="poster poster_{@variant}">
      <div class="poster_body poster_body_{@variant}">
        <div class="poster_block poster_block_{@variant}">
        <h2 class="poster_title"><xsl:value-of select="widget:title"/></h2>
        <p class="poster_text"><xsl:value-of select="widget:text"/></p>
            <!--
        <p class="poster_links">
          <a class="poster_link poster_link_havestue" href="/da/havestuen/">Mere om havestuen</a>
        </p>
            -->
        </div>
      </div>
    </div>
</xsl:template>

<xsl:template match="widget:login">
  <div class="part_authentication" id="part_authentication_{generate-id()}">
        <form class="part_authentication_form" action="{$path}services/authentication">
            <p class="part_authentication_field">
                <label class="part_authentication_label">Brugernavn</label>
                <input class="part_authentication_input part_authentication_username common_input" name="username"/>
            </p>
            <p class="part_authentication_field">
                <label class="part_authentication_label">Kodeord</label>
                <input class="part_authentication_input part_authentication_password common_input" type="password" name="password"/>
            </p>
            <p class="part_authentication_actions">
                <button class="part_authentication_login common_button">Log ind</button>
            </p>
        </form>
    </div>

  <script type="text/javascript">_editor.loadPart({
        name : 'Authentication',$ready : function() {
            new op.part.Authentication({element : 'part_authentication_<xsl:value-of select="generate-id()"/>'});
        }
    });
    </script>
</xsl:template>

</xsl:stylesheet>