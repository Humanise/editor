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
 xmlns:widget="http://uri.in2isoft.com/onlinepublisher/part/widget/1.0/"
 exclude-result-prefixes="p f h n o util widget"
 >
<xsl:output encoding="UTF-8" method="xml" omit-xml-declaration="yes"/>
<xsl:include href="../../basic/xslt/util.xsl"/>

<xsl:template match="p:page">
<xsl:call-template name="util:doctype"/>
<html>
  <xsl:call-template name="util:html-attributes"/>
  <head>
    <title>
      <xsl:choose>
        <xsl:when test="//p:page/p:context/p:home[@page=//p:page/@id]">
          <xsl:text>Humanise - </xsl:text>
          <xsl:choose>
            <xsl:when test="//p:page/p:meta/p:language='en'"><xsl:text>Software for humans</xsl:text></xsl:when>
            <xsl:otherwise><xsl:text>Software til mennesker</xsl:text></xsl:otherwise>
          </xsl:choose>
        </xsl:when>
        <xsl:otherwise>
          <xsl:value-of select="@title"/><xsl:text> - </xsl:text><xsl:value-of select="f:frame/@title"/>
        </xsl:otherwise>
      </xsl:choose>
    </title>
    <meta name="viewport" content="user-scalable=yes, initial-scale = 1, maximum-scale = 10, minimum-scale = 0.2"/>
    <link rel="shortcut icon" href="{$path}style/humanise/gfx/favicon.ico" type="image/x-icon" />
    <xsl:call-template name="util:metatags"/>
    <xsl:call-template name="util:js"/>

    <xsl:call-template name="util:css">
      <xsl:with-param name="async" select="'true'"/>
      <xsl:with-param name="inline" select="'true'"/>
      <xsl:with-param name="ie-lt-9" select="'true'"/>
      <xsl:with-param name="ie-lt-8" select="'true'"/>
    </xsl:call-template>

    <xsl:if test="//widget:hero">
      <xsl:call-template name="util:inline-css">
        <xsl:with-param name="file" select="'style/humanise/css/hero.css'"/>
      </xsl:call-template>
    </xsl:if>

    <xsl:call-template name="util:load-font">
      <xsl:with-param name="href" select="'http://fonts.googleapis.com/css?family=Lato:300,400,700,900'"/>
      <xsl:with-param name="family" select="'Lato'"/>
      <xsl:with-param name="weights" select="'300,400,700'"/>
    </xsl:call-template>
  </head>
  <body>
    <div class="layout">
      <div>
        <xsl:attribute name="class">
          <xsl:text>layout_head</xsl:text>
          <xsl:if test="//widget:hero"><xsl:text> layout_head-hero</xsl:text></xsl:if>
        </xsl:attribute>
        <div class="layout_head_body">
          <ul class="layout_menu">
            <xsl:apply-templates select="f:frame/h:hierarchy/h:item"/>
          </ul>
          <xsl:call-template name="search"/>
        </div>
      </div>
      <div class="layout_middle">
        <div class="layout_middle_top">
          <xsl:call-template name="secondlevel"/>
          <xsl:call-template name="util:languages"/>
          <xsl:comment/>
        </div>
        <div class="layout_body">
          <!--
          <xsl:if test="//p:page/@id=//p:context/p:home/@page">
            <div id="poster">
              <div id="poster_loader">0%</div>
              <div id="poster_left"><xsl:comment/></div>
              <div id="poster_right"><xsl:comment/></div>
            </div>
            <script type="text/javascript">require(['Poster'],function() {new Poster();});</script>
          </xsl:if>
            -->
          <xsl:apply-templates select="p:content"/>
        </div>
      </div>

      <div class="layout_base">
        <div class="layout_info">
          <div class="layout_info_about">
            <xsl:choose>
              <xsl:when test="//p:page/p:meta/p:language='en'">
                <h2 class="layout_info_header">About Humanise</h2>
                <p>We focus on user experience and design. We seek out the most simple and essential solution. We believe that machines should work for people. We think that knowledge should be free and accessible to all. We hope you agree :-)</p>
                <p class="layout_info_more">
                  <a href="{$path}om/" class="common_link"><span class="common_link_text">More about Humanise »</span></a>
                </p>
              </xsl:when>
              <xsl:otherwise>
                <xsl:call-template name="util:parameter">
                    <xsl:with-param name="name" select="'about'"/>
                    <xsl:with-param name="default">
                      <h2 class="layout_info_header">Om Humanise</h2>
                      <p>Vores focus er på brugeroplevelse og design. Vi leder altid efter den mest enkle og essentielle løsning. Vi tror på at maskinen skal arbejde for mennesket. Vi mener at viden bør være fri og tilgængelig for alle. Vi håber du er enig :-)</p>
                    </xsl:with-param>
                </xsl:call-template>
                <p class="layout_info_more">
                  <a href="{$path}om/" class="common_link"><span class="common_link_text">Mere om Humanise »</span></a>
                </p>
              </xsl:otherwise>
            </xsl:choose>
          </div>
          <div class="layout_info_contact">
            <xsl:choose>
              <xsl:when test="//p:page/p:meta/p:language='en'"><h2>Contact</h2></xsl:when>
              <xsl:otherwise><h2 class="layout_info_header">Kontakt</h2></xsl:otherwise>
            </xsl:choose>
            <xsl:call-template name="util:parameter">
                <xsl:with-param name="name" select="'contact'"/>
                <xsl:with-param name="default">
                  <p><strong class="layout_info_name">Jonas Brinkmann Munk</strong></p>
                  <p><a class="common_link" href="mailto:jonasmunk@mac.com"><span class="common_link_text">jonasmunk@mac.com</span></a> · <a href="tel:004528776365" class="layout_info_phone">+45 28 77 63 65</a></p>
                  <p><strong class="layout_info_name">Kenni Graversen</strong></p>
                  <p><a class="common_link" href="mailto:gr@versen.dk"><span class="common_link_text">gr@versen.dk</span></a> · <a href="tel:004522486153" class="layout_info_phone">+45 22 48 61 53</a></p>
                </xsl:with-param>
            </xsl:call-template>
          </div>
        </div>
      </div>
    </div>
    <div class="layout_footer">
      <a class="layout_design">Designet og udviklet af Humanise</a>
      <!--
        <xsl:apply-templates select="f:frame/f:text/f:bottom"/>
        <xsl:apply-templates select="f:frame/f:links/f:bottom"/>
        -->
    </div>
    <xsl:call-template name="util:googleanalytics"/>
  </body>
</html>
</xsl:template>

<xsl:template match="p:content">
<div>
  <xsl:choose>
    <xsl:when test="../f:frame/h:hierarchy/h:item/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item or ../f:frame/f:newsblock or ../f:frame/f:userstatus">
      <xsl:attribute name="class">layout_content layout_content-sidebar</xsl:attribute>
    </xsl:when>
    <xsl:otherwise>
      <xsl:attribute name="class">layout_content</xsl:attribute>
    </xsl:otherwise>
  </xsl:choose>
  <xsl:if test="../f:frame/h:hierarchy/h:item/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item or ../f:frame/f:newsblock or ../f:frame/f:userstatus">
    <div class="layout_sidebar">
      <xsl:call-template name="thirdlevel"/>
      <xsl:apply-templates select="../f:frame/f:newsblock"/>
      <xsl:comment/>
    </div>
  </xsl:if>
  <div>
    <xsl:choose>
      <xsl:when test="../f:frame/h:hierarchy/h:item/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item or ../f:frame/f:newsblock or ../f:frame/f:userstatus">
        <xsl:attribute name="class">layout_inner_content layout_inner_content-sidebar</xsl:attribute>
      </xsl:when>
      <xsl:otherwise>
        <xsl:attribute name="class">layout_inner_content</xsl:attribute>
      </xsl:otherwise>
    </xsl:choose>
    <xsl:if test="//p:context/p:translation">
      <p class="layout_translation">
      <xsl:for-each select="//p:context/p:translation">
        <a class="common">
          <xsl:call-template name="util:link"/>
          <xsl:choose>
            <xsl:when test="@language='da'">
              <span><xsl:text>Denne side på dansk</xsl:text></span>
            </xsl:when>
            <xsl:when test="@language='en'">
              <span><xsl:text>This page in english</xsl:text></span>
            </xsl:when>
            <xsl:otherwise>
              <span>This page in <xsl:value-of select="@language"/></span>
            </xsl:otherwise>
          </xsl:choose>
        </a>
      </xsl:for-each>
      </p>
    </xsl:if>
    <xsl:apply-templates/>
    <xsl:comment/>
  </div>
</div>
</xsl:template>


<!--            User status                 -->



<xsl:template match="f:userstatus">
  <xsl:choose>
    <xsl:when test="$userid>0">
      <span class="userstatus">Bruger: <strong><xsl:value-of select="$usertitle"/></strong></span>
      <xsl:text> · </xsl:text>
      <a href="./?id={@page}&amp;logout=true" class="common">Log ud</a>
    </xsl:when>
    <xsl:otherwise>
      <a href="./?id={@page}" class="common">Log ind</a>
    </xsl:otherwise>
  </xsl:choose>
</xsl:template>



<xsl:template match="h:hierarchy/h:item">
  <xsl:if test="not(@hidden='true')">
    <li>
      <xsl:attribute name="class">
        <xsl:text>layout_menu_item</xsl:text>
      <xsl:choose>
        <xsl:when test="position()>1 and //p:page/@id=@page"> layout_menu_item-selected</xsl:when>
        <xsl:when test="position()>1 and descendant-or-self::*/@page=//p:page/@id"> layout_menu_item-highlighted</xsl:when>
        <xsl:when test="position()=1"> layout_menu_item-first</xsl:when>
      </xsl:choose>
      </xsl:attribute>
      <a>
      <xsl:attribute name="class">
        <xsl:text>layout_menu_link</xsl:text>
      <xsl:choose>
        <xsl:when test="position()>1 and //p:page/@id=@page"> layout_menu_link-selected</xsl:when>
        <xsl:when test="position()>1 and descendant-or-self::*/@page=//p:page/@id"> layout_menu_link-highlighted</xsl:when>
        <xsl:when test="position()=1"> layout_menu_link-first</xsl:when>
      </xsl:choose>
      </xsl:attribute>
        <xsl:call-template name="util:link"/>
        <span><xsl:value-of select="@title"/></span>
      </a>
    </li>
  </xsl:if>
</xsl:template>

<xsl:template name="secondlevel">
  <xsl:if test="//f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item[not(@hidden='true')]">
    <ul>
      <xsl:attribute name="class">
        <xsl:text>submenu</xsl:text>
      </xsl:attribute>
      <xsl:apply-templates select="//f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item"/>
    </ul>
  </xsl:if>
</xsl:template>

<xsl:template match="h:hierarchy/h:item/h:item">
  <xsl:variable name="class">
    <xsl:text>submenu_link</xsl:text>
    <xsl:choose>
      <xsl:when test="//p:page/@id=@page"><xsl:text> submenu_link-selected</xsl:text></xsl:when>
      <xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text> submenu_link-highlighted</xsl:text></xsl:when>
    </xsl:choose>
  </xsl:variable>
  <xsl:if test="not(@hidden='true')">
    <li class="submenu_item">
    <a class="{$class}">
      <xsl:call-template name="util:link"/>
      <xsl:value-of select="@title"/>
    </a>
    </li>
  </xsl:if>
</xsl:template>

<xsl:template match="h:hierarchy/h:item/h:item//h:item[not(@hidden='true')]">
  <xsl:variable name="class">
    <xsl:text>sidemenu_link</xsl:text>
    <xsl:choose>
      <xsl:when test="//p:page/@id=@page"><xsl:text> sidemenu_link-selected</xsl:text></xsl:when>
      <xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text> sidemenu_link-highlighted</xsl:text></xsl:when>
    </xsl:choose>
  </xsl:variable>
  <li class="sidemenu_item">
    <a class="{$class}">
      <xsl:call-template name="util:link"/>
      <xsl:value-of select="@title"/>
    </a>
    <xsl:if test="descendant-or-self::*/@page=//p:page/@id and h:item">
      <ul class="sidemenu_sub"><xsl:apply-templates/></ul>
    </xsl:if>
  </li>
</xsl:template>

<xsl:template name="thirdlevel">
<xsl:if test="//f:frame/h:hierarchy/h:item/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item">
  <ul>
    <xsl:attribute name="class">
      <xsl:text>sidemenu</xsl:text>
    </xsl:attribute>
    <xsl:apply-templates select="//f:frame/h:hierarchy/h:item/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item"/>
  </ul>
</xsl:if>
</xsl:template>

<xsl:template match="h:item">
  <xsl:variable name="style">
    <xsl:choose>
      <xsl:when test="//p:page/@id=@page"><xsl:text>selected</xsl:text></xsl:when>
      <xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text>highlighted</xsl:text></xsl:when>
      <xsl:otherwise>standard</xsl:otherwise>
    </xsl:choose>
  </xsl:variable>
  <xsl:if test="not(@hidden='true')">
    <li>
      <a class="{$style}">
        <xsl:call-template name="util:link"/>
        <span><xsl:value-of select="@title"/></span>
      </a>
    <xsl:if test="descendant-or-self::*/@page=//p:page/@id and h:item">
      <ul><xsl:apply-templates/></ul>
    </xsl:if>
    </li>
  </xsl:if>
</xsl:template>





<!--            Links              -->


<xsl:template match="f:links/f:top">
  <div class="links_top">
    <div>
      <xsl:apply-templates select="//f:frame/f:userstatus"/> ·
      <a title="Udskriv siden" class="common" href="?id={//p:page/@id}&amp;print=true">Udskriv</a>
      <xsl:apply-templates/>
    </div>
  </div>
</xsl:template>

<xsl:template match="f:links/f:bottom">
  <div class="layout_links">
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

<xsl:template match="f:links/f:top/f:link">
  <span>&#160;&#183;&#160;</span>
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




<!--            News              -->





<xsl:template match="f:newsblock">
  <div class="layout_news">
    <h2><xsl:value-of select="@title"/></h2>
    <xsl:apply-templates/>
  </div>
</xsl:template>

<xsl:template match="f:newsblock//o:object">
  <div class="layout_news_item">
    <h3><xsl:value-of select="o:title"/></h3>
    <p class="layout_news_text">
      <xsl:apply-templates select="o:note"/>
    </p>
    <xsl:apply-templates select="o:sub/n:news/n:startdate"/>
    <xsl:apply-templates select="o:links"/>
  </div>
</xsl:template>

<xsl:template match="f:newsblock//o:links">
  <p class="layout_news_links">
    <xsl:apply-templates/>
  </p>
</xsl:template>

<xsl:template match="f:newsblock//o:note">
  <xsl:apply-templates/>
</xsl:template>

<xsl:template match="f:newsblock//o:break">
  <br/>
</xsl:template>

<xsl:template match="f:newsblock//n:startdate">
  <p class="layout_news_date"> <xsl:value-of select="@day"/>/<xsl:value-of select="@month"/><!--/<xsl:value-of select="substring(@year,3,2)"/>--></p>
</xsl:template>

<xsl:template match="f:newsblock//o:link">
  <xsl:if test="position()>1"><xsl:text> </xsl:text></xsl:if>
  <a title="{@alternative}" class="common">
    <xsl:call-template name="util:link"/>
    <span>
      <xsl:value-of select="@title"/>
    </span>
  </a>
</xsl:template>



<!--                  Search                     -->


<xsl:template name="search">
  <xsl:if test="f:frame/f:search">
    <form action="{$path}" method="get" class="layout_search" accept-charset="UTF-8">
      <svg class="layout_search_icon" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill="black" d="M19.129,18.164l-4.518-4.52c1.152-1.373,1.852-3.143,1.852-5.077c0-4.361-3.535-7.896-7.896-7.896 c-4.361,0-7.896,3.535-7.896,7.896s3.535,7.896,7.896,7.896c1.934,0,3.705-0.698,5.078-1.853l4.52,4.519 c0.266,0.268,0.699,0.268,0.965,0C19.396,18.863,19.396,18.431,19.129,18.164z M8.567,15.028c-3.568,0-6.461-2.893-6.461-6.461 s2.893-6.461,6.461-6.461c3.568,0,6.46,2.893,6.46,6.461S12.135,15.028,8.567,15.028z"></path></svg>
      <label class="layout_search_label" for="layout_search">Søgning</label>
      <span class="layout_search_body">
        <input type="text" class="layout_search_text" id="layout_search" name="query" placeholder="Search here"/>
        <input type="hidden" name="id" value="{f:frame/f:search/@page}"/>
        <xsl:for-each select="f:frame/f:search/f:types/f:type">
        <input type="hidden" name="{@unique}" value="on"/>
        <button type="reset" class="layout_search_reset" tabindex="-1">Nulstil</button>
        </xsl:for-each>
        <input type="submit" class="layout_search_submit" value="Søg" tabindex="-1"/>
      </span>
    </form>
  </xsl:if>
</xsl:template>



<!--                    Widgets                 -->

<xsl:template match="widget:hero">
  <div class="hero">
    <div class="hero_title"><span class="hero_title_prefix">Humanise</span> <span class="hero_title_suffix">Editor</span></div>
    <div class="hero_info">
      <div class="hero_text">Simpel redigering af websites</div>
      <a class="hero_button" href="/produkter/onlinepublisher/">Lær mere</a>
    </div>
    <div class="hero_rays"><xsl:comment/></div>
    <div class="hero_pencil"><xsl:comment/></div>
  </div>
</xsl:template>

<xsl:template match="widget:happy-xmas">
  <div class="happy-xmas">
    <h1 style="color: red;"><xsl:value-of select="widget:title"/></h1>
  </div>
</xsl:template>

<xsl:template match="widget:call-to-action">
  <div class="call-to-action">
    <p class="call-to-action-text"><xsl:value-of select="widget:text"/></p>
    <xsl:apply-templates select="widget:button"/>
  </div>
</xsl:template>

<xsl:template match="widget:call-to-action/widget:button">
  <a class="call-to-action-button">
    <xsl:call-template name="util:link-href"/>
    <xsl:value-of select="."/>
  </a>
</xsl:template>

<xsl:template match="widget:placards">
  <ul class="layout_placards">
    <li class="layout_placards_item">
      <a href="{$path}produkter/onlinepublisher/" class="layout_placards_link">
        <strong>Humanise <em>Editor</em></strong> - <span class="layout_placards_text layout_placards_text_editor">Simpelt værktøj til opbygning og redigering af hjemmesider</span>
      </a>
    </li>
    <li class="layout_placards_item">
      <a href="{$path}produkter/onlineobjects/" class="layout_placards_link">
        <strong>Online<em>Objects</em></strong> - <span class="layout_placards_text layout_placards_text_objects">Fleksibelt grundsystem til web-applikationer</span>
      </a>
    </li>
    <li class="layout_placards_item">
      <a href="{$path}teknologi/hui/" class="layout_placards_link layout_placards_link_last">
        <strong>Humanise <em>UI</em></strong> - <span class="layout_placards_text layout_placards_text_hui">Intuitiv, avanceret og effektiv brugerflade</span>
      </a>
    </li>
  </ul>
</xsl:template>



<xsl:template match="widget:focus">
  <div class="focus">
    <!--
    <svg xmlns="http://www.w3.org/2000/svg" width="100" height="104.5068418" viewBox="0 0 830.62627 868.06061"><path class="focus_hand" d="M366.397 357.57c-4.584-13.04-14.78-48.642-13-78 .504-8.334 1.96-44.772 1-55-.625-6.658.66-14.08 0-21-1.256-13.147-5.794-54.513-6-63-.898-37.105-20.935-92.01-5-123 7.664-14.906 36.716-24.745 55-11 25.354 19.06 19.54 79.686 19 121-.466 35.613-3.27 83.953 2 118 5.736 37.067 4.356 82.285-13 105-9.84 12.876-29.368 22.646-40 7M164.397 81.57c36.275-7.416 45.383 28.944 54 57 4.21 13.71 8.386 27.403 12 40 11.914 41.528 24.886 97.083 38 141 5.69 19.058 18.296 38.445 16 56-1.457 11.124-11 24.924-22 25-14.127.096-34.31-39.105-39-52-5.564-15.298-16.178-40.89-22-55-10.41-25.227-25.817-52.337-36-80-9.087-24.69-16.683-43.74-22-75-2.174-12.78-5.145-24.93-3-34 2.793-11.818 13.905-20.938 24-23M14.397 367.57c28.526-4.17 45.962 20.786 66 34 19.046 12.56 37.58 21.62 50 32 9.02 7.537 20.462 21.07 23 32 5.706 24.578-14.356 52.19-33 53-13.693.594-27.58-16.792-37-27-21.187-22.96-43.792-40.96-64-64-12.675-14.453-33.745-49.59-5-60M781.397 510.57c-9.577 2.88-21.075 12.41-30 20-7.502 6.378-19.462 17.32-25 25-4.04 5.6-11.83 16.632-17 24-7.364 10.49-24.936 26.577-37.584 38.96-9.263 9.065-33.254 19.26-49 18-8.964-.72-14.685-6.59-22.5-13.5a132.123 132.123 0 0 1-22.5-25c-5.454-7.7-13.416-33.46-19-46-5.58-12.534-4.37-40.588-3-49.5 2-13 3.927-17.674 5.584-28.96 4.27-29.083-7.037-44.878-3.584-67.04 1.578-10.136 5.73-23.698 10.584-32.96 4.67-8.91 20.78-90.253 25-103 3.683-11.13 4.35-23.586 8-35 3.394-10.613 6.572-21.527 10-33 7.516-25.168 23.295-55.638 15-86-4.64-16.985-29.935-44.638-49-42-17.195 2.377-22.06 11-30.584 23.96-5.802 8.82-7.138 22.595-9 31-2.364 10.663-1.324 28.128-2 39-1.118 17.995-3.067 38.83-5 58-1.802 17.864-5.842 30.99-10 47-6.987 26.896-26.645 42.845-29.416 70.04-1.246 12.228-2.725 25.112-5.584 33.96-4.222 13.06-12.023 20.203-26.416 24.04-23.635 6.298-36.02-11.484-58-9-20.92 2.363-28.584 27.96-48 39-29.097 16.543-41.584 6.46-59.084 15.96-20.06 10.89-31.112 31.91-41.5 38-14.5 8.5-25.1 7.788-34.416 11.04-24.568 8.572-51.93 34.015-50 64 .64 9.946 8.933 23.075 14 31 6.73 10.525 11.532 20.742 16 30 9.792 20.288 22.59 42.34 29 62 7.582 23.25 8.08 53.07 10 78 .875 11.355 5.415 22.54 9 34 3.74 11.955 5.726 24.303 10 33 9.003 18.317 31.964 36.573 58 39 27.758 2.585 45.975-5.616 67-11 14.39-3.685 29.696-7.085 46-10 31.223-5.586 68.323-10.783 92-21 17.864-7.712 35.76-20.443 52-32 30.306-21.57 57.08-39.4 75-70 7.165-12.24 17.27-19.96 26-34 9.22-14.832 25.717-30.193 43-38 13.734-6.205 24.187-13.502 40-19 16.418-5.71 31.352-16.817 44-29 12.34-11.887 26.395-24.737 30-43 6.638-33.63-14.21-60.168-48-50M460.905 625.61c-1.33 7.454-5.527 16.608-8 27-2.73 11.477-.963 25.095-3 32-3.55 12.025-28.002 26.34-45 12-8.395-7.08-11.525-24.55-16-33-6.302-11.895-24.192-25.384-28-39-3.99-14.265 2.347-30.808-1-49-1.028-5.58-6.828-12.664-9-18-4.168-10.23-10.204-24.587 4-28 14.73-3.54 25.175 20.67 36 27 8.753 5.12 20.678 7.453 30 12 20.928 10.21 46.14 22.575 40 57"/></svg>-->
    <div class="focus_hand"><xsl:comment/></div>
    <div class="focus_text">
      <p>Vores <strong>focus</strong> er på <strong>brugeroplevelse</strong> og <strong>design</strong>. Vi leder altid efter den mest <strong>enkle</strong> og essentielle løsning.</p>
      <p>Vi tror på at maskinen skal arbejde for <strong>mennesket</strong>. Vi mener at <strong>viden</strong> bør være <strong>fri</strong> og tilgængelig for <strong>alle</strong>.</p>
    </div>
  </div>
  <xsl:call-template name="util:inline-css">
    <xsl:with-param name="file" select="'style/humanise/css/focus.css'"/>
  </xsl:call-template>
</xsl:template>


<!--
<xsl:call-template name="util:style-build">
  <xsl:with-param name="async" select="'true'"/>
</xsl:call-template>
  -->
<!--
<xsl:call-template name="util:load-font">
  <xsl:with-param name="href" select="'/style/basic/fonts/Lato/LatoLatin/latolatinfonts_weights.css'"/>
  <xsl:with-param name="family" select="'Lato'"/>
  <xsl:with-param name="weights" select="'300,400,700,900'"/>
</xsl:call-template>-->
<!--
<xsl:call-template name="util:load-font">
  <xsl:with-param name="href" select="'http://fonts.googleapis.com/css?family=Heebo:100,300,400,500'"/>
  <xsl:with-param name="family" select="'Heebo'"/>
  <xsl:with-param name="weights" select="'100,300,400,500'"/>
</xsl:call-template>
-->
  <!--
    MYRIAD PRO...
  <script>
    <xsl:comment>
    <![CDATA[
    (function(d) {
      var config = {
        kitId: 'gpd5jdr',
        scriptTimeout: 3000,
        async: true
      },
      h=d.documentElement,t=setTimeout(function(){h.className=h.className.replace(/\bwf-loading\b/g,"")+" wf-inactive";},config.scriptTimeout),tk=d.createElement("script"),f=false,s=d.getElementsByTagName("script")[0],a;h.className+=" wf-loading";tk.src='https://use.typekit.net/'+config.kitId+'.js';tk.async=true;tk.onload=tk.onreadystatechange=function(){a=this.readyState;if(f||a&&a!="complete"&&a!="loaded")return;f=true;clearTimeout(t);try{Typekit.load(config)}catch(e){}};s.parentNode.insertBefore(tk,s)
    })(document);
    ]]>
    </xsl:comment>
  </script>
  -->

</xsl:stylesheet>