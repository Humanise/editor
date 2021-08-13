<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
 xmlns="http://www.w3.org/1999/xhtml"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:ig="http://uri.in2isoft.com/onlinepublisher/part/imagegallery/1.0/"
 xmlns:o="http://uri.in2isoft.com/onlinepublisher/class/object/1.0/"
 xmlns:i="http://uri.in2isoft.com/onlinepublisher/class/image/1.0/"
 xmlns:util="http://uri.in2isoft.com/onlinepublisher/util/"
 exclude-result-prefixes="o i ig"
 >


  <xsl:template match="ig:imagegallery">
    <xsl:choose>
      <xsl:when test="ig:display/@variant='masonry'">
        <div id="part_imagegallery_{../../@id}" class="part_imagegallery_masonry">
          <xsl:for-each select="o:object[@type='image']">
            <a href="{$path}services/images/?id={@id}">
              <xsl:attribute name="data">{"id" : <xsl:value-of select="@id"/>,"width" : <xsl:value-of select="o:sub/i:image/i:width"/>,"height" : <xsl:value-of select="o:sub/i:image/i:height"/>}</xsl:attribute>
              <xsl:value-of select="o:title"/>
            </a>
            <xsl:text> </xsl:text>
          </xsl:for-each>
        </div>
      </xsl:when>
      <xsl:otherwise>
        <div id="part_imagegallery_{generate-id()}">
          <xsl:attribute name="class">
            <xsl:text>part_imagegallery</xsl:text>
            <xsl:if test="ig:display/@framed='true'"><xsl:text> part_imagegallery_framed</xsl:text></xsl:if>
            <xsl:if test="ig:display/@variant='changing'"><xsl:text> part_imagegallery_changing</xsl:text></xsl:if>
          </xsl:attribute>
          <xsl:apply-templates select="o:object[@type='image']"/>
          <xsl:comment/>
        </div>
      </xsl:otherwise>
    </xsl:choose>
    <script>
      <xsl:text>_editor.loadPart({</xsl:text>
        <xsl:text>name : 'ImageGallery',</xsl:text>
        <xsl:text>$ready : function() {</xsl:text>
        <xsl:text>var images = [</xsl:text>

          <xsl:for-each select="o:object"><xsl:if test="position()>1">,</xsl:if>{id:<xsl:value-of select="@id"/>,width:<xsl:value-of select="o:sub/i:image/i:width"/>,height:<xsl:value-of select="o:sub/i:image/i:height"/>,text:'<xsl:value-of select="o:note"/>'}</xsl:for-each>
        <xsl:text>];</xsl:text>
        <xsl:text>var part = new op.part.ImageGallery({</xsl:text>element : 'part_imagegallery_<xsl:value-of select="generate-id()"/>',variant : '<xsl:value-of select="ig:display/@variant"/>',editor : <xsl:value-of select="$editor='true'"/>,images : images,height:'<xsl:value-of select="ig:display/@height"/>'});}});</script>
  </xsl:template>

  <xsl:template match="o:object[@type='image']">
    <xsl:variable name="height">
      <xsl:choose>
        <xsl:when test="../ig:display/@height"><xsl:value-of select="../ig:display/@height"/></xsl:when>
        <xsl:otherwise>64</xsl:otherwise>
      </xsl:choose>
    </xsl:variable>
    <xsl:variable name="width">
      <xsl:choose>
        <xsl:when test="../ig:display/@width"><xsl:value-of select="../ig:display/@width"/></xsl:when>
        <xsl:otherwise>
          <xsl:value-of select="round(number(o:sub/i:image/i:width) div number(o:sub/i:image/i:height) * $height)"/>
        </xsl:otherwise>
      </xsl:choose>
    </xsl:variable>
    <xsl:variable name="method">
      <xsl:choose>
        <xsl:when test="../ig:display/@width">crop</xsl:when>
        <xsl:otherwise>fit</xsl:otherwise>
      </xsl:choose>
    </xsl:variable>
    <xsl:variable name="url">
      <xsl:choose>
        <xsl:when test="$editor='true'">javascript:void();</xsl:when>
        <xsl:otherwise><xsl:value-of select="$path"/>services/images/?id=<xsl:value-of select="@id"/></xsl:otherwise>
      </xsl:choose>
    </xsl:variable>
    <span class="part_imagegallery_item">
      <xsl:if test="../ig:display/@variant='changing' and position()>1">
          <xsl:attribute name="style">display: none;</xsl:attribute>
      </xsl:if>
      <xsl:call-template name="util:wrap-in-frame">
        <xsl:with-param name="variant" select="../ig:display/@frame"/>
        <xsl:with-param name="content">
          <a href="{$url}" data-id="{@id}" data-index="{position()-1}">
            <xsl:if test="../ig:display/@variant='changing' and position()=2">
              <xsl:attribute name="style">display: inline-block;</xsl:attribute>
            </xsl:if>
            <xsl:if test="../ig:display/@show-title='true'">
              <span class="part_imagegallery_title common_font"><xsl:value-of select="o:title"/></span>
            </xsl:if>
            <img src="{$path}services/images/?id={@id}&amp;height={$height}&amp;width={$width}&amp;method={$method}" style="height: {$height}px; width: {$width}px;" alt="" class="part_imagegallery_image" id="part_imagegallery_{generate-id()}"/>
          </a>
          </xsl:with-param>
        </xsl:call-template>
      </span>
      <xsl:text> </xsl:text>
  </xsl:template>

</xsl:stylesheet>