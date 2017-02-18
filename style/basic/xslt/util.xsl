<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
 xmlns="http://www.w3.org/1999/xhtml"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/publishing/page/1.0/"
 xmlns:f="http://uri.in2isoft.com/onlinepublisher/publishing/frame/1.0/"
 xmlns:h="http://uri.in2isoft.com/onlinepublisher/publishing/hierarchy/1.0/"
 xmlns:res="http://uri.in2isoft.com/onlinepublisher/resource/"
 xmlns:header="http://uri.in2isoft.com/onlinepublisher/part/header/1.0/"
 xmlns:text="http://uri.in2isoft.com/onlinepublisher/part/text/1.0/"
 xmlns:movie="http://uri.in2isoft.com/onlinepublisher/part/movie/1.0/"
 xmlns:util="http://uri.in2isoft.com/onlinepublisher/util/"
 xmlns:part="http://uri.in2isoft.com/onlinepublisher/part/1.0/"
 xmlns:style="http://uri.in2isoft.com/onlinepublisher/style/1.0/"
 xmlns:php="http://php.net/xsl"
 exclude-result-prefixes="p f h header text util part movie php res style"
 >

<xsl:variable name="timestamp-query">
  <xsl:if test="$urlrewrite!='true'">
    <xsl:text>?version=</xsl:text><xsl:value-of select="$timestamp"/>
  </xsl:if>
</xsl:variable>

<xsl:variable name="timestamp-url">
  <xsl:if test="$urlrewrite='true'">
    <xsl:text>version</xsl:text><xsl:value-of select="$timestamp"/><xsl:text>/</xsl:text>
  </xsl:if>
</xsl:variable>


<!-- Links -->

<xsl:template name="util:link">
  <xsl:attribute name="title"><xsl:value-of select="@alternative"/></xsl:attribute>
  <xsl:choose>
    <xsl:when test="$editor='true'">
      <xsl:attribute name="href">javascript://</xsl:attribute>
      <xsl:choose>
        <xsl:when test="@id">
          <xsl:attribute name="onclick">
          linkController.linkWasClicked({
            id : <xsl:value-of select="@id"/>
            , event : event
            , node : this
            <xsl:if test="ancestor::part:part/@id">,part : <xsl:value-of select="ancestor::part:part/@id"/></xsl:if>
          }); return false;
          </xsl:attribute>
          <xsl:attribute name="oncontextmenu">
            linkController.linkMenu({
              id : <xsl:value-of select="@id"/>
              , event : event
              , node : this
              <xsl:if test="ancestor::part:part/@id">,part : <xsl:value-of select="ancestor::part:part/@id"/></xsl:if>
            });
          </xsl:attribute>
        </xsl:when>
        <xsl:otherwise>
          <xsl:attribute name="onclick">return false;</xsl:attribute>
        </xsl:otherwise>
      </xsl:choose>
      <xsl:if test="@part-id">
        <span class="editor_link_bound"><xsl:comment/></span>
      </xsl:if>
    </xsl:when>
    <xsl:otherwise>
      <xsl:choose>
        <xsl:when test="@path and $preview='false'">
          <xsl:attribute name="href">
            <xsl:value-of select="$navigation-path"/>
            <xsl:choose>
              <xsl:when test="starts-with(@path,'/')">
                <xsl:value-of select="substring(@path,2)"/>
              </xsl:when>
              <xsl:otherwise>
                <xsl:value-of select="@path"/>
              </xsl:otherwise>
            </xsl:choose>
          </xsl:attribute>
        </xsl:when>
        <xsl:when test="@page">
          <xsl:attribute name="href"><xsl:value-of select="$navigation-path"/>?id=<xsl:value-of select="@page"/></xsl:attribute>
        </xsl:when>
        <xsl:when test="@page-reference">
          <xsl:attribute name="href"><xsl:value-of select="$navigation-path"/>?id=<xsl:value-of select="@page-reference"/></xsl:attribute>
        </xsl:when>
        <xsl:when test="@url">
          <xsl:attribute name="href"><xsl:value-of select="@url"/></xsl:attribute>
        </xsl:when>
        <xsl:when test="@file">
          <xsl:attribute name="href"><xsl:value-of select="$navigation-path"/>?file=<xsl:value-of select="@file"/><xsl:if test="@target='_download'">&amp;download=true</xsl:if></xsl:attribute>
        </xsl:when>
        <xsl:when test="@email">
          <xsl:attribute name="href">mailto:<xsl:value-of select="@email"/></xsl:attribute>
        </xsl:when>
      </xsl:choose>
      <xsl:choose>
        <xsl:when test="@target='_blank'">
          <xsl:attribute name="onclick">try {window.open(this.getAttribute('href')); return false;} catch (ignore) {}</xsl:attribute>
        </xsl:when>
        <xsl:when test="@target and @target!='_self' and @target!='_download'">
          <xsl:attribute name="target"><xsl:value-of select="@target"/></xsl:attribute>
        </xsl:when>
      </xsl:choose>
    </xsl:otherwise>
  </xsl:choose>
</xsl:template>

<xsl:template name="util:link-href">
  <xsl:choose>
    <xsl:when test="@path and $preview='false'">
      <xsl:value-of select="$navigation-path"/>
    </xsl:when>
    <xsl:when test="@page">
      <xsl:value-of select="$navigation-path"/>?id=<xsl:value-of select="@page"/>
    </xsl:when>
    <xsl:when test="@page-reference">
      <xsl:value-of select="$navigation-path"/>?id=<xsl:value-of select="@page-reference"/>
    </xsl:when>
    <xsl:when test="@url">
      <xsl:attribute name="href"><xsl:value-of select="@url"/></xsl:attribute>
    </xsl:when>
    <xsl:when test="@file">
      <xsl:value-of select="$navigation-path"/>?file=<xsl:value-of select="@file"/><xsl:if test="@target='_download'">&amp;download=true</xsl:if>
    </xsl:when>
    <xsl:when test="@email">
      mailto:<xsl:value-of select="@email"/>
    </xsl:when>
  </xsl:choose>
</xsl:template>

<xsl:template name="util:link-url">
  <xsl:param name="node"/>
  <xsl:choose>
    <xsl:when test="$node/@path and $preview='false'">
      <xsl:value-of select="$navigation-path"/>
      <xsl:choose>
        <xsl:when test="starts-with(@path,'/')">
          <xsl:value-of select="substring(@path,2)"/>
        </xsl:when>
        <xsl:otherwise>
          <xsl:value-of select="@path"/>
        </xsl:otherwise>
      </xsl:choose>
    </xsl:when>
    <xsl:when test="$node/@page">
      <xsl:value-of select="$navigation-path"/>?id=<xsl:value-of select="$node/@page"/>
    </xsl:when>
    <xsl:when test="$node/@page-reference">
      <xsl:value-of select="$navigation-path"/>?id=<xsl:value-of select="$node/@page-reference"/>
    </xsl:when>
    <xsl:when test="$node/@url">
      <xsl:attribute name="href"><xsl:value-of select="$node/@url"/></xsl:attribute>
    </xsl:when>
    <xsl:when test="$node/@file">
      <xsl:value-of select="$navigation-path"/>?file=<xsl:value-of select="$node/@file"/><xsl:if test="$node/@target='_download'">&amp;download=true</xsl:if>
    </xsl:when>
    <xsl:when test="$node/@email">
      mailto:<xsl:value-of select="$node/@email"/>
    </xsl:when>
  </xsl:choose>
</xsl:template>


<!-- Style -->


<xsl:template name="util:media-before">
  <xsl:text>@media screen </xsl:text>
  <xsl:if test="@min-width">and (min-width: <xsl:value-of select="@min-width"/>)</xsl:if>
  <xsl:if test="@width-is-above">and (min-width: <xsl:value-of select="@width-is-above"/>)</xsl:if>
  <xsl:if test="@max-width">and (max-width: <xsl:value-of select="@max-width"/>)</xsl:if>
  <xsl:if test="@width-less-than">and (max-width: <xsl:value-of select="@width-less-than"/>)</xsl:if>
  <xsl:text>{</xsl:text>
</xsl:template>

<xsl:template name="util:media-after">
  <xsl:text>}</xsl:text>
</xsl:template>

<xsl:template name="util:rules">
  <xsl:value-of select="@css"/>
  <xsl:for-each select="rule">
    <xsl:value-of select="@name"/><xsl:text>:</xsl:text><xsl:value-of select="@value"/><xsl:text>;</xsl:text>
  </xsl:for-each>
  <xsl:for-each select="*[not(name()='rule')]">
    <xsl:value-of select="concat(name(),':',text(),@of,';')"/>
  </xsl:for-each>
  <xsl:for-each select="@*[not(name()='name' or name()='css')]">
    <xsl:value-of select="concat(name(), ': ', ., ';')"/>
  </xsl:for-each>
</xsl:template>


<!-- Uncategorized -->

<xsl:template name="util:googleanalytics">
  <xsl:param name="code" select="//p:meta/p:analytics/@key"/>
  <xsl:if test="not($preview='true') and $code!='' and $statistics='true'">
    <script>(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
ga('create', '<xsl:value-of select="$code"/>', {siteSpeedSampleRate : 20});ga('send', 'pageview');</script>
  </xsl:if>
</xsl:template>

<xsl:template name="util:doctype">
    <xsl:text disable-output-escaping='yes'>&lt;!DOCTYPE html&gt;
</xsl:text>
</xsl:template>

<xsl:template name="util:html-attributes">
  <xsl:if test="//p:page/p:meta/p:language">
    <xsl:if test="//p:page/p:meta/p:language/text()">
      <xsl:attribute name="lang"><xsl:value-of select="//p:page/p:meta/p:language"/></xsl:attribute>
      <xsl:attribute name="xml:lang"><xsl:value-of select="//p:page/p:meta/p:language"/></xsl:attribute>
    </xsl:if>
  </xsl:if>
</xsl:template>

<xsl:template name="util:title">
  <title>
    <xsl:if test="not(//p:page/@id=//p:context/p:home/@page)">
      <xsl:value-of select="//p:page/@title"/>
      <xsl:text> - </xsl:text>
    </xsl:if>
    <xsl:value-of select="//f:frame/@title"/>
  </title>
</xsl:template>

<xsl:template name="util:metatags">
  <meta http-equiv="content-type" content="text/html; charset=utf-8"></meta>
  <meta name="robots" content="index,follow"></meta>
  <meta property="og:title" content="{//p:page/@title}"/>
  <meta property="og:site_name" content="{//f:frame/@title}"/>
  <meta property="og:url" content="{$absolute-page-path}" />
  <xsl:if test="p:meta/p:description">
    <meta property="og:description" content="{p:meta/p:description}" />
    <meta name="Description" content="{p:meta/p:description}"></meta>
  </xsl:if>
  <xsl:for-each select="//p:page/p:context/p:translation[@language and @language!=$language and not(@language=//p:page/@language)]">
    <link rel="alternate" hreflang="{@language}">
      <xsl:attribute name="href">
        <xsl:choose>
        <xsl:when test="@path">
          <xsl:choose>
            <xsl:when test="starts-with(@path,'/')">
              <xsl:value-of select="@path"/>
            </xsl:when>
            <xsl:otherwise>
              <xsl:value-of select="$navigation-path"/><xsl:value-of select="@path"/>
            </xsl:otherwise>
          </xsl:choose>
        </xsl:when>
        <xsl:when test="@page">
          <xsl:value-of select="$navigation-path"/>?id=<xsl:value-of select="@page"/>
        </xsl:when>
        </xsl:choose>
      </xsl:attribute>
    </link>
  </xsl:for-each>
</xsl:template>

<xsl:template name="util:feedback">
  <xsl:param name="text">Feedback</xsl:param>
  <p class="layout_feedback">
    <a class="common" href="javascript://" onclick="op.feedback(this)"><span><xsl:value-of select="$text"/></span></a>
  </p>
</xsl:template>

<xsl:template name="util:watermark">
  <xsl:comment>
    _    _                             _
   | |  | |                           (_)
   | |__| |_   _ _ __ ___   __ _ _ __  _ ___  ___
   |  __  | | | | '_ ` _ \ / _` | '_ \| / __|/ _ \
   | |  | | |_| | | | | | | (_| | | | | \__ \  __/
   |_|  |_|\__,_|_| |_| |_|\__,_|_| |_|_|___/\___|

   SOFTWARE FOR HUMANS     http://www.humanise.dk/
    </xsl:comment>
</xsl:template>

<xsl:template name="util:userstatus">
  <xsl:if test="//f:userstatus">
    <div class="layout_userstatus">
      <xsl:choose>
        <xsl:when test="$userid>0">
          <strong><xsl:text>Bruger: </xsl:text></strong><xsl:value-of select="$usertitle"/>
          <xsl:text> </xsl:text>
          <a href="{$path}?id={//f:userstatus/@page}&amp;logout=true" class="common common_link"><span><xsl:text>log ud</xsl:text></span></a>
        </xsl:when>
        <xsl:otherwise>
            <span>Ikke logget ind</span>
            <xsl:text> </xsl:text>
            <a href="{$path}?id={//f:userstatus/@page}" class="common common_link"><span>log ind</span></a>
        </xsl:otherwise>
      </xsl:choose>
    </div>
  </xsl:if>
</xsl:template>

<xsl:template name="util:parameter">
  <xsl:param name="name" />
  <xsl:param name="default" />
  <div>
    <xsl:attribute name="data-editable">{"name":"<xsl:value-of select="$name"/>"}</xsl:attribute>
    <xsl:choose>
      <xsl:when test="//p:parameter[@name=$name]">
        <xsl:value-of select="//p:parameter[@name=$name]" disable-output-escaping="yes"/>
      </xsl:when>
      <xsl:otherwise>
        <xsl:copy-of select="$default"/>
      </xsl:otherwise>
    </xsl:choose>
  </div>
</xsl:template>

<xsl:template name="util:viewport">
  <meta name="viewport" content="user-scalable=yes, initial-scale = 1, maximum-scale = 10, minimum-scale = 0.2"/>
</xsl:template>

<!-- Scripts -->

<xsl:template name="util:js">
  <xsl:comment><![CDATA[[if lt IE 9]>
  <script src="]]><xsl:value-of select="$path"/><xsl:value-of select="$timestamp-url"/>hui/bin/compatibility.min.js<xsl:value-of select="$timestamp-query"/><![CDATA[" data-movable="false"></script>
  <![endif]]]></xsl:comment>
  <script type="text/javascript">
    <xsl:text disable-output-escaping="yes">//&lt;![CDATA[
</xsl:text>
<xsl:value-of select="php:function('DesignService::getInlineJS',$design,$development)" disable-output-escaping="yes"/>
  _editor.context = '<xsl:value-of select="$path"/>';
  require(['hui.ui'],function() {hui.ui.context='<xsl:value-of select="$path"/>';hui.ui.language='<xsl:value-of select="$language"/>';});require(['op'],function() {op.context='<xsl:value-of select="$path"/>';op.page.id=<xsl:value-of select="@id"/>;op.page.template='<xsl:value-of select="$template"/>';op.page.path='<xsl:value-of select="$path"/>';op.page.pagePath='<xsl:value-of select="$page-path"/>';op.user={username:'<xsl:value-of select="$username"/>',id:<xsl:value-of select="$userid"/>,internal:<xsl:value-of select="$internal-logged-in"/>};op.preview=<xsl:value-of select="$preview"/>;op.ignite();})
    <xsl:text disable-output-escaping="yes">
//]]&gt;</xsl:text>
  </script>

  <xsl:variable name="query">
    <xsl:choose>
      <xsl:when test="$development='true' and $preview='true'">?preview=true&amp;development=true</xsl:when>
      <xsl:when test="$development='true'">?development=true</xsl:when>
      <xsl:when test="$preview='true'">?preview=true</xsl:when>
    </xsl:choose>
  </xsl:variable>

  <xsl:variable name="url">
    <xsl:value-of select="$path"/>
    <xsl:value-of select="$timestamp-url"/>
    <xsl:text>api/style/</xsl:text>
    <xsl:value-of select="$design"/>
    <xsl:text>.js</xsl:text>
    <xsl:value-of select="$query"/>
  </xsl:variable>

  <xsl:choose>
    <xsl:when test="$preview='true'">
      <script src="{$url}"><xsl:comment/></script>
    </xsl:when>
    <xsl:otherwise>
      <script src="{$url}" async="async" defer="defer"><xsl:comment/></script>
    </xsl:otherwise>
  </xsl:choose>

  <xsl:if test="$preview='true' and $mini!='true'">
    <script src="editor.js?version={$timestamp}"><xsl:comment/></script>
    <script src="{$path}{$timestamp-url}Editor/Template/{$template}/js/editor.php{$timestamp-query}"><xsl:comment/></script>
  </xsl:if>
</xsl:template>






<!-- Style -->


<xsl:template name="util:inline-css">
  <xsl:param name="file"/>
  <style type="text/css">
    <xsl:value-of select="php:function('DesignService::getCustomInlineCSS',$design,$file,$development)" disable-output-escaping="yes"/>
  </style>
</xsl:template>

<xsl:template name="util:css">
  <xsl:param name="async" select="'false'"/>
  <xsl:param name="inline" select="'false'"/>
  <xsl:param name="ie-lt-9" select="'false'"/>
  <xsl:param name="ie-lt-8" select="'false'"/>
  <xsl:param name="ie-lt-7" select="'false'"/>
  <xsl:param name="ie-8" select="'false'"/>
  <xsl:param name="ie-7" select="'false'"/>
  <xsl:param name="ie-6" select="'false'"/>

  <xsl:if test="$inline='true'">
    <style type="text/css">
      <xsl:value-of select="php:function('DesignService::getInlineCSS',$design,$development)" disable-output-escaping="yes"/>
      <xsl:text>/**/</xsl:text>
    </style>
  </xsl:if>

  <xsl:if test="$template!='document'">
    <link rel="stylesheet" type="text/css" href="{$path}{$timestamp-url}style/basic/css/{$template}.css"/>
  </xsl:if>


  <xsl:variable name="query">
    <xsl:choose>
      <xsl:when test="$development='true' and $preview='true'">?preview=true&amp;development=true</xsl:when>
      <xsl:when test="$development='true'">?development=true</xsl:when>
      <xsl:when test="$preview='true'">?preview=true</xsl:when>
    </xsl:choose>
  </xsl:variable>

  <xsl:variable name="url">
    <xsl:value-of select="$path"/>
    <xsl:value-of select="$timestamp-url"/>
    <xsl:text>api/style/</xsl:text>
    <xsl:value-of select="$design"/>
    <xsl:text>.css</xsl:text>
    <xsl:value-of select="$query"/>
  </xsl:variable>

  <xsl:choose>
    <xsl:when test="$async='true'">
      <xsl:call-template name="util:async-css">
        <xsl:with-param name="href"><xsl:value-of select="$url"/></xsl:with-param>
      </xsl:call-template>
    </xsl:when>
    <xsl:otherwise>
      <link rel="stylesheet" type="text/css" href="{$url}"/>
    </xsl:otherwise>
  </xsl:choose>

  <xsl:call-template name="util:_style-dynamic"/>

  <xsl:comment><![CDATA[[if lt IE 7]><link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$path"/><xsl:value-of select="$timestamp-url"/>hui/css/msie6.css<xsl:value-of select="$timestamp-query"/><![CDATA["></link><![endif]]]></xsl:comment>
  <xsl:comment><![CDATA[[if IE 7]><link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$path"/><xsl:value-of select="$timestamp-url"/>hui/css/msie7.css<xsl:value-of select="$timestamp-query"/><![CDATA["></link><![endif]]]></xsl:comment>

  <xsl:if test="$ie-lt-9='true'">
    <xsl:comment><![CDATA[[if lt IE 9]><link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$path"/><xsl:value-of select="$timestamp-url"/>style/<xsl:value-of select="$design"/><![CDATA[/css/msie_lt9.css"> </link><![endif]]]></xsl:comment>
  </xsl:if>
  <xsl:if test="$ie-lt-8='true'">
    <xsl:comment><![CDATA[[if lt IE 9]><link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$path"/><xsl:value-of select="$timestamp-url"/>style/<xsl:value-of select="$design"/><![CDATA[/css/msie_lt8.css"> </link><![endif]]]></xsl:comment>
  </xsl:if>
  <xsl:if test="$ie-lt-7='true'">
    <xsl:comment><![CDATA[[if lt IE 7]><link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$path"/><xsl:value-of select="$timestamp-url"/>style/<xsl:value-of select="$design"/><![CDATA[/css/msie6.css"> </link><![endif]]]></xsl:comment>
  </xsl:if>
  <xsl:if test="$ie-6='true'">
    <xsl:comment><![CDATA[[if lt IE 7]><link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$path"/><xsl:value-of select="$timestamp-url"/>style/<xsl:value-of select="$design"/><![CDATA[/css/msie6.css"> </link><![endif]]]></xsl:comment>
  </xsl:if>
  <xsl:if test="$ie-7='true'">
    <xsl:comment><![CDATA[[if IE 7]><link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$path"/><xsl:value-of select="$timestamp-url"/>style/<xsl:value-of select="$design"/><![CDATA[/css/msie7.css"> </link><![endif]]]></xsl:comment>
  </xsl:if>
  <xsl:if test="$ie-8='true'">
    <xsl:comment><![CDATA[[if IE 8]><link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$path"/><xsl:value-of select="$timestamp-url"/>style/<xsl:value-of select="$design"/><![CDATA[/css/msie8.css"> </link><![endif]]]></xsl:comment>
  </xsl:if>

  <xsl:call-template name="util:css-resources"/>
</xsl:template>

<xsl:template name="util:css-resources">
  <xsl:for-each select="//res:css[@inline]">
    <xsl:call-template name="util:inline-css">
      <xsl:with-param name="file" select="string(@inline)"/>
    </xsl:call-template>
  </xsl:for-each>
  <xsl:for-each select="//res:css[@async]">
    <xsl:call-template name="util:async-css">
      <xsl:with-param name="href">
        <xsl:value-of select="$path"/><xsl:value-of select="$timestamp-url"/><xsl:value-of select="@async"/>
      </xsl:with-param>
    </xsl:call-template>
  </xsl:for-each>
</xsl:template>

<xsl:template name="util:async-css">
    <xsl:param name="href"/>
    <noscript class="js-async">
    <link rel="stylesheet" type="text/css" href="{$href}" media="all"/>
    </noscript>
  <xsl:comment><![CDATA[[if lt IE 9]><link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$href"/><![CDATA[" media="all"/><![endif]]]></xsl:comment>
</xsl:template>

<xsl:template name="util:load-font">
    <xsl:param name="href"/>
    <xsl:param name="family"/>
    <xsl:param name="weights" select="'400'"/>
    <xsl:param name="class" select="'font'"/>
    <script type="text/javascript">
      <xsl:text disable-output-escaping="yes">//&lt;![CDATA[
      </xsl:text>
      <xsl:value-of select="php:function('DesignService::getCustomInlineJS','style/basic/js/boot_fonts.js',$development)" disable-output-escaping="yes"/>
_editor.loadFont({href:'<xsl:value-of select="$href"/>',family:'<xsl:value-of select="$family"/>',cls:'<xsl:value-of select="$class"/>'<xsl:if test="$weights!=''">,weights:'<xsl:value-of select="$weights"/>'.split(',')</xsl:if>});
      <xsl:text disable-output-escaping="yes">
  //]]&gt;</xsl:text>
    </script>
</xsl:template>

<xsl:template name="util:_style-dynamic">
  <xsl:if test="//header:style[contains(@font-family,'Cabin Sketch')] or //text:style[contains(@font-family,'Cabin Sketch')]">
    <link href='http://fonts.googleapis.com/css?family=Cabin+Sketch:bold' rel='stylesheet' type='text/css'/>
  </xsl:if>
  <xsl:if test="//header:style[contains(@font-family,'Droid Sans')] or //text:style[contains(@font-family,'Droid Sans')]">
    <link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css' />
  </xsl:if>
  <xsl:if test="//header:style[contains(@font-family,'Just Me Again Down Here')] or //text:style[contains(@font-family,'Just Me Again Down Here')]">
    <link href='http://fonts.googleapis.com/css?family=Just+Me+Again+Down+Here' rel='stylesheet' type='text/css'/>
  </xsl:if>
  <xsl:if test="//header:style[contains(@font-family,'Crimson Text')] or //text:style[contains(@font-family,'Crimson Text')]">
    <link href='http://fonts.googleapis.com/css?family=Crimson+Text:regular,bold' rel='stylesheet' type='text/css' />
  </xsl:if>
  <xsl:if test="//header:style[contains(@font-family,'Luckiest Guy')] or //text:style[contains(@font-family,'Luckiest Guy')]">
    <link href='http://fonts.googleapis.com/css?family=Luckiest+Guy' rel='stylesheet' type='text/css' />
  </xsl:if>
  <xsl:if test="//header:style[contains(@font-family,'Dancing Script')] or //text:style[contains(@font-family,'Dancing Script')]">
    <link href='http://fonts.googleapis.com/css?family=Dancing+Script' rel='stylesheet' type='text/css' />
  </xsl:if>
</xsl:template>




<!-- Dates -->

<xsl:template name="util:weekday">
  <xsl:param name="node"/>
  <xsl:choose>
    <xsl:when test="$language='en'">
      <xsl:choose>
        <xsl:when test="$node/@weekday=0">Sunday</xsl:when>
        <xsl:when test="$node/@weekday=1">Monday</xsl:when>
        <xsl:when test="$node/@weekday=2">Tuesday</xsl:when>
        <xsl:when test="$node/@weekday=3">Wednesday</xsl:when>
        <xsl:when test="$node/@weekday=4">Thursday</xsl:when>
        <xsl:when test="$node/@weekday=5">Friday</xsl:when>
        <xsl:when test="$node/@weekday=6">Saturday</xsl:when>
      </xsl:choose>
    </xsl:when>
    <xsl:otherwise>
      <xsl:choose>
        <xsl:when test="$node/@weekday=0">Søndag</xsl:when>
        <xsl:when test="$node/@weekday=1">Mandag</xsl:when>
        <xsl:when test="$node/@weekday=2">Tirsdag</xsl:when>
        <xsl:when test="$node/@weekday=3">Onsdag</xsl:when>
        <xsl:when test="$node/@weekday=4">Torsdag</xsl:when>
        <xsl:when test="$node/@weekday=5">Fredag</xsl:when>
        <xsl:when test="$node/@weekday=6">Lørdag</xsl:when>
      </xsl:choose>
    </xsl:otherwise>
  </xsl:choose>
</xsl:template>

<xsl:template name="util:month">
  <xsl:param name="node"/>
  <xsl:choose>
    <xsl:when test="$language='en'">
      <xsl:choose>
        <xsl:when test="number($node/@month)=1">January</xsl:when>
        <xsl:when test="number($node/@month)=2">February</xsl:when>
        <xsl:when test="number($node/@month)=3">March</xsl:when>
        <xsl:when test="number($node/@month)=4">April</xsl:when>
        <xsl:when test="number($node/@month)=5">May</xsl:when>
        <xsl:when test="number($node/@month)=6">June</xsl:when>
        <xsl:when test="number($node/@month)=7">July</xsl:when>
        <xsl:when test="number($node/@month)=8">August</xsl:when>
        <xsl:when test="number($node/@month)=9">September</xsl:when>
        <xsl:when test="number($node/@month)=10">October</xsl:when>
        <xsl:when test="number($node/@month)=11">November</xsl:when>
        <xsl:when test="number($node/@month)=12">December</xsl:when>
      </xsl:choose>
    </xsl:when>
    <xsl:otherwise>
      <xsl:choose>
        <xsl:when test="number($node/@month)=1">januar</xsl:when>
        <xsl:when test="number($node/@month)=2">februar</xsl:when>
        <xsl:when test="number($node/@month)=3">marts</xsl:when>
        <xsl:when test="number($node/@month)=4">april</xsl:when>
        <xsl:when test="number($node/@month)=5">maj</xsl:when>
        <xsl:when test="number($node/@month)=6">juni</xsl:when>
        <xsl:when test="number($node/@month)=7">juli</xsl:when>
        <xsl:when test="number($node/@month)=8">august</xsl:when>
        <xsl:when test="number($node/@month)=9">september</xsl:when>
        <xsl:when test="number($node/@month)=10">oktober</xsl:when>
        <xsl:when test="number($node/@month)=11">november</xsl:when>
        <xsl:when test="number($node/@month)=12">december</xsl:when>
      </xsl:choose>
    </xsl:otherwise>
  </xsl:choose>
</xsl:template>

<xsl:template name="util:long-date-time">
  <xsl:param name="node"/>
  <xsl:choose>
    <xsl:when test="$language='en'">
      <xsl:call-template name="util:weekday"><xsl:with-param name="node" select="$node"/></xsl:call-template>
      <xsl:text>, </xsl:text>
      <xsl:value-of select="number($node/@day)"/><xsl:text> </xsl:text>
      <xsl:call-template name="util:month"><xsl:with-param name="node" select="$node"/></xsl:call-template>
      <xsl:if test="number($node/@hour)>0 or number($node/@minute)>0">
        <xsl:text> at </xsl:text><xsl:value-of select="$node/@hour"/>:<xsl:value-of select="$node/@minute"/>
      </xsl:if>
    </xsl:when>
    <xsl:otherwise>
      <xsl:call-template name="util:weekday"><xsl:with-param name="node" select="$node"/></xsl:call-template>
      <xsl:text> d. </xsl:text>
      <xsl:value-of select="number($node/@day)"/><xsl:text>. </xsl:text>
      <xsl:call-template name="util:month"><xsl:with-param name="node" select="$node"/></xsl:call-template>
      <xsl:if test="number($node/@hour)>0 or number($node/@minute)>0">
        <xsl:text> kl. </xsl:text><xsl:value-of select="$node/@hour"/>:<xsl:value-of select="$node/@minute"/>
      </xsl:if>
    </xsl:otherwise>
  </xsl:choose>
</xsl:template>







<!-- Languages -->

<xsl:template name="util:languages">
  <xsl:param name="tag" select="'span'"/>
  <xsl:element name="{$tag}">
    <xsl:attribute name="class">layout_languages</xsl:attribute>
    <xsl:for-each select="//p:page/p:context/p:home[@language and @language!=$language and not(@language=//p:page/p:context/p:translation/@language)]">
      <xsl:call-template name="util:language"/>
    </xsl:for-each>
    <xsl:for-each select="//p:page/p:context/p:translation">
      <xsl:call-template name="util:language"/>
    </xsl:for-each>
    <xsl:comment/>
  </xsl:element>
</xsl:template>

<xsl:template name="util:language">
  <a class="layout_language layout_language_{@language}">
    <xsl:call-template name="util:link"/>
    <span class="layout_language_text">
    <xsl:choose>
      <xsl:when test="@language='da'">Dansk version</xsl:when>
      <xsl:when test="@language='en'">English version</xsl:when>
      <xsl:otherwise><xsl:value-of select="@language"/></xsl:otherwise>
    </xsl:choose>
    </span>
  </a><xsl:text> </xsl:text>
</xsl:template>





<!-- Navigation -->

<xsl:template name="util:menu-top-level">
  <xsl:if test="//f:frame/h:hierarchy/h:item[not(@hidden='true')]">
    <ul class="layout_menu_top">
      <xsl:for-each select="//f:frame/h:hierarchy/h:item">
        <xsl:if test="not(@hidden='true')">
          <li>
            <xsl:attribute name="class">
              <xsl:text>layout_menu_top_item</xsl:text>
              <xsl:choose>
                <xsl:when test="//p:page/@id=@page"> layout_menu_top_item_selected</xsl:when>
                <xsl:when test="descendant-or-self::*/@page=//p:page/@id"> layout_menu_top_item_highlighted</xsl:when>
              </xsl:choose>
            </xsl:attribute>
            <a>
              <xsl:attribute name="class">
                <xsl:text>layout_menu_top_link</xsl:text>
                <xsl:choose>
                  <xsl:when test="//p:page/@id=@page"> layout_menu_top_link_selected</xsl:when>
                  <xsl:when test="descendant-or-self::*/@page=//p:page/@id"> layout_menu_top_link_highlighted</xsl:when>
                </xsl:choose>
              </xsl:attribute>
              <xsl:call-template name="util:link"/>
              <span><xsl:value-of select="@title"/></span>
            </a>
          </li>
        </xsl:if>
      </xsl:for-each>
    </ul>
  </xsl:if>
</xsl:template>

<xsl:template name="util:navigation-first-level">
  <xsl:if test="//f:frame/h:hierarchy/h:item[not(@hidden='true')]">
    <ul class="layout_navigation_first">
      <xsl:for-each select="//f:frame/h:hierarchy/h:item">
        <xsl:if test="not(@hidden='true')">
          <li>
            <xsl:choose>
              <xsl:when test="//p:page/@id=@page"><xsl:attribute name="class">layout_selected</xsl:attribute></xsl:when>
              <xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:attribute name="class">layout_highlighted</xsl:attribute></xsl:when>
            </xsl:choose>
            <a>
              <xsl:call-template name="util:link"/>
              <span><xsl:value-of select="@title"/></span>
            </a>
          </li>
        </xsl:if>
      </xsl:for-each>
    </ul>
  </xsl:if>
</xsl:template>

<xsl:template name="util:navigation-second-level">
  <xsl:if test="//f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item[not(@hidden='true')]">
    <ul class="layout_navigation_second">
      <xsl:for-each select="//f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item">
        <xsl:if test="not(@hidden='true')">
          <li>
            <xsl:choose>
              <xsl:when test="//p:page/@id=@page"><xsl:attribute name="class">layout_selected</xsl:attribute></xsl:when>
              <xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:attribute name="class">layout_highlighted</xsl:attribute></xsl:when>
            </xsl:choose>
            <a>
              <xsl:call-template name="util:link"/>
              <span><xsl:value-of select="@title"/></span>
            </a>
          </li>
        </xsl:if>
      </xsl:for-each>
    </ul>
  </xsl:if>
</xsl:template>

<xsl:template name="util:navigation-third-level">
  <xsl:if test="//f:frame/h:hierarchy/h:item/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item[not(@hidden='true')]">
    <ul class="layout_navigation_third">
      <xsl:for-each select="//f:frame/h:hierarchy/h:item/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item">
        <xsl:if test="not(@hidden='true')">
          <li>
            <xsl:choose>
              <xsl:when test="//p:page/@id=@page"><xsl:attribute name="class">layout_selected</xsl:attribute></xsl:when>
              <xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:attribute name="class">layout_highlighted</xsl:attribute></xsl:when>
            </xsl:choose>
            <a>
              <xsl:call-template name="util:link"/>
              <span><xsl:value-of select="@title"/></span>
            </a>
          </li>
        </xsl:if>
      </xsl:for-each>
    </ul>
  </xsl:if>
</xsl:template>

<xsl:template name="util:hierarchy-after-first-level">
  <xsl:if test="//f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item">
    <ul>
      <xsl:for-each select="//f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item">
        <xsl:call-template name="util:hierarchy-item-iterator"/>
      </xsl:for-each>
    </ul>
  </xsl:if>
</xsl:template>

<xsl:template name="util:hierarchy-all-levels">
  <ul>
    <xsl:for-each select="//f:frame/h:hierarchy/h:item">
      <xsl:call-template name="util:hierarchy-item-iterator"/>
    </xsl:for-each>
  </ul>
</xsl:template>

<xsl:template name="util:hierarchy-item-iterator">
  <xsl:if test="not(@hidden='true')">
    <li>
      <a>
        <xsl:choose>
          <xsl:when test="//p:page/@id=@page"><xsl:attribute name="class">selected</xsl:attribute></xsl:when>
          <xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:attribute name="class">highlighted</xsl:attribute></xsl:when>
        </xsl:choose>
        <xsl:call-template name="util:link"/>
        <span><xsl:value-of select="@title"/></span>
      </a>
      <xsl:if test="descendant-or-self::*/@page=//p:page/@id and h:item">
        <ul>
          <xsl:for-each select="h:item">
            <xsl:call-template name="util:hierarchy-item-iterator"/>
          </xsl:for-each>
        </ul>
      </xsl:if>
    </li>
  </xsl:if>
</xsl:template>




<!-- Shared -->



<xsl:template name="util:wrap-in-frame">
  <xsl:param name="variant"/>
  <xsl:param name="adaptive"/>
  <xsl:param name="content"/>
  <xsl:param name="max-width"/>

  <xsl:choose>
    <xsl:when test="$variant!=''">
      <span>
        <xsl:attribute name="class">
          <xsl:text>shared_frame shared_frame_</xsl:text><xsl:value-of select="$variant"/>
          <xsl:if test="$adaptive='true'"> shared_frame-adaptive</xsl:if>
        </xsl:attribute>
        <xsl:if test="$max-width!=''">
          <xsl:attribute name="style">
            <xsl:text>max-width:</xsl:text>
            <xsl:value-of select="$max-width"/>
            <xsl:text>;</xsl:text>
          </xsl:attribute>
        </xsl:if>
        <span class="shared_frame_{$variant}_content">
          <xsl:copy-of select="$content"/>
        </span>
      </span>
    </xsl:when>
    <xsl:otherwise>
      <xsl:copy-of select="$content"/>
    </xsl:otherwise>
  </xsl:choose>
</xsl:template>



<!-- deprecated -->
<xsl:template name="util:hierarchy-first-level">
  <ul>
    <xsl:for-each select="//f:frame/h:hierarchy/h:item">
      <xsl:if test="not(@hidden='true')">
        <li>
        <xsl:choose>
          <xsl:when test="//p:page/@id=@page"><xsl:attribute name="class">selected</xsl:attribute></xsl:when>
          <xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:attribute name="class">highlighted</xsl:attribute></xsl:when>
        </xsl:choose>
        <a>
          <xsl:call-template name="util:link"/>
          <span><xsl:value-of select="@title"/></span>
        </a>
        </li>
      </xsl:if>
    </xsl:for-each>
  </ul>
</xsl:template>

<!-- Deprecated -->
<xsl:template name="util:hierarchy-second-level">
  <xsl:if test="//f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item">
    <ul class="case_sub_navigation">
      <xsl:for-each select="//f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item">
        <xsl:if test="not(@hidden='true')">
          <li>
          <xsl:choose>
            <xsl:when test="//p:page/@id=@page"><xsl:attribute name="class">selected</xsl:attribute></xsl:when>
            <xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:attribute name="class">highlighted</xsl:attribute></xsl:when>
          </xsl:choose>
          <a>
            <xsl:call-template name="util:link"/>
            <span><xsl:value-of select="@title"/></span>
          </a>
          </li>
        </xsl:if>
      </xsl:for-each>
    </ul>
  </xsl:if>
</xsl:template>


</xsl:stylesheet>