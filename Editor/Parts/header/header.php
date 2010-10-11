<?
/**
 * @package OnlinePublisher
 * @subpackage Parts.Header
 */
require_once($basePath.'Editor/Classes/Parts/LegacyPartController.php');
require_once($basePath.'Editor/Classes/Services/XslService.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class PartHeader extends LegacyPartController {

	var $id;
	
	function PartHeader($id=0) {
		parent::LegacyPartController('header');
		$this->id = $id;
	}
	
	function sub_import(&$node) {
		$xml = '<?xml version="1.0" encoding="ISO-8859-1"?>'.$node->toString();
		$xsl = '<?xml version="1.0" encoding="ISO-8859-1"?>
		<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
		 xmlns:t="http://uri.in2isoft.com/onlinepublisher/part/header/1.0/" exclude-result-prefixes="t">
		<xsl:output method="text" encoding="ISO-8859-1"/>

		<xsl:template match="t:header"><xsl:apply-templates/></xsl:template>
		<xsl:template match="t:break"><xsl:text>'."\n".'</xsl:text></xsl:template>
		<xsl:template match="t:strong">[s]<xsl:apply-templates/>[s]</xsl:template>
		<xsl:template match="t:em">[e]<xsl:apply-templates/>[e]</xsl:template>
		<xsl:template match="t:del">[slet]<xsl:apply-templates/>[slet]</xsl:template>
		<xsl:template match="t:link"><xsl:apply-templates/></xsl:template>

		</xsl:stylesheet>';

		$level = $node->getAttribute('level');
		$text = XslService::transform($xml,$xsl);
		
		$style = $this->_parseXMLStyle($node->selectNodes('style',1));
		
		$sql = "update part_header set".
		" level=".$level.
		",text=".Database::text($text).
		",textalign=".Database::text($style['textalign']).
		",fontsize=".Database::text($style['fontsize']).
		",fontfamily=".Database::text($style['fontfamily']).
		",lineheight=".Database::text($style['lineheight']).
		",fontweight=".Database::text($style['fontweight']).
		",wordspacing=".Database::text($style['wordspacing']).
		",letterspacing=".Database::text($style['letterspacing']).
		",textdecoration=".Database::text($style['textdecoration']).
		",textindent=".Database::text($style['textindent']).
		",texttransform=".Database::text($style['texttransform']).
		",fontstyle=".Database::text($style['fontstyle']).
		",fontvariant=".Database::text($style['fontvariant']).
		",color=".Database::text($style['color']).
		" where part_id=".$this->id;
		Database::update($sql);
	}
}
?>