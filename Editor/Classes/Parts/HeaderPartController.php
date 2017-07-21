<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class HeaderPartController extends PartController
{
  function HeaderPartController() {
    parent::PartController('header');
  }

  function isLiveEnabled() {
    return true;
  }


  static function createPart() {
    $part = new HeaderPart();
    $part->setText('Velkommen');
    $part->setLevel(1);
    $part->save();
    return $part;
  }

  function _transfer($object,$keys) {
    foreach ($keys as $key=>$type) {
      if (is_int($key)) {
        $key = $type;
        $type = 'string';
      }
      if (Request::exists($key)) {
        $method = 'set'.ucfirst($key);
        $value = $type=='int' ? Request::getInt($key) : Request::getString($key);
        if (method_exists($object,$method)) {
          $object->$method($value);
        } else {
          Log::debug('Method: '.$method.' does not exists on...');
          Log::debug($object);
        }
      }
    }
  }

  function getFromRequest($id) {
    $part = HeaderPart::load($id);
    $this->_transfer($part,[
      'text',
      'level'=>'int',
      'color',
      'fontSize',
      'lineHeight',
      'textAlign',
      'fontFamily',
      'letterSpacing',
      'fontWeight',
      'fontStyle',
      'wordSpacing',
      'textIndent',
      'textTransform',
      'fontVariant',
      'textDecoration'
    ]);
    return $part;
  }

  function display($part,$context) {
    return $this->render($part,$context);
  }

  function editor($part,$context) {
    return
    '<textarea class="part_header common_font common_header common_header-'.$part->getLevel().' part_header-'.$part->getLevel().'" name="text" id="part_header_textarea" style="border: 1px solid lightgrey; width: 100%; background: transparent; padding: 0; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; '.$this->buildCSSStyle($part).'">'.
        Strings::escapeEncodedXML($part->getText()).
    '</textarea>'.
    '<input type="hidden" name="level" value="'.$part->getLevel().'"/>'.
    '<input type="hidden" name="fontSize" value="'.Strings::escapeEncodedXML($part->getFontSize()).'"/>'.
    '<input type="hidden" name="fontFamily" value="'.Strings::escapeEncodedXML($part->getFontFamily()).'"/>'.
    '<input type="hidden" name="textAlign" value="'.Strings::escapeEncodedXML($part->getTextAlign()).'"/>'.
    '<input type="hidden" name="lineHeight" value="'.Strings::escapeEncodedXML($part->getLineHeight()).'"/>'.
    '<input type="hidden" name="fontWeight" value="'.Strings::escapeEncodedXML($part->getFontWeight()).'"/>'.
    '<input type="hidden" name="fontStyle" value="'.Strings::escapeEncodedXML($part->getFontStyle()).'"/>'.
    '<input type="hidden" name="color" value="'.Strings::escapeEncodedXML($part->getColor()).'"/>'.
    '<input type="hidden" name="wordSpacing" value="'.Strings::escapeEncodedXML($part->getWordSpacing()).'"/>'.
    '<input type="hidden" name="letterSpacing" value="'.Strings::escapeEncodedXML($part->getLetterSpacing()).'"/>'.
    '<input type="hidden" name="textIndent" value="'.Strings::escapeEncodedXML($part->getTextIndent()).'"/>'.
    '<input type="hidden" name="textTransform" value="'.Strings::escapeEncodedXML($part->getTextTransform()).'"/>'.
    '<input type="hidden" name="fontVariant" value="'.Strings::escapeEncodedXML($part->getFontVariant()).'"/>'.
    '<input type="hidden" name="textDecoration" value="'.Strings::escapeEncodedXML($part->getTextDecoration()).'"/>'.
    '<script src="'.ConfigurationService::getBaseUrl().'Editor/Parts/header/script.js" type="text/javascript" charset="utf-8"></script>';
  }

  function getIndex($part) {
    $context = new PartContext();
    $text = $part->getText();
    $text = $context->decorateForIndex($text);
    return $text;
  }

  function getSectionClass($part) {
    return 'part_section_header-'.$part->getLevel();
  }

  function buildSub($part,$context) {
    $text = $part->getText();
    $text = Strings::escapeSimpleXML($text);
    $text = $context->decorateForBuild($text,$part->getId());
    $text = Strings::insertLineBreakTags($text,'<break/>');
    return
      '<header level="'.$part->getLevel().'" xmlns="'.$this->getNamespace().'">'.
      $this->buildXMLStyle($part).
      $text.
      '</header>';
  }

  function importSub($node,$part) {
    $xml = '<?xml version="1.0" encoding="ISO-8859-1"?>'.DOMUtils::getInnerXML($node);
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
    $text = XslService::transform($xml,$xsl);
    if ($header = DOMUtils::getFirstChildElement($node,'header')) {
      $level = intval($header->getAttribute('level'));
    }
    if ($level<1 || $level>6) {
      $level = 1;
    }
    $part->setLevel($level);
    $this->parseXMLStyle($part,DOMUtils::getFirstDescendant($node,'style'));

    $part->setText($text);
  }

  function getUI() {
    return [
      [
        'icon' => 'monochrome/text',
        'key' => 'text',
        'body' => '
          <formula name="textFormula" padding="10">
            <fields>
            <field label="Level">
              <segmented key="level">
                <option text="1" value="1"/>
                <option text="2" value="2"/>
                <option text="3" value="3"/>
                <option text="4" value="4"/>
                <option text="5" value="5"/>
                <option text="6" value="6"/>
              </segmented>
            </field>
            <field label="Size">
              <style-length-input adaptive="true" key="fontSize"/>
            </field>
            <field label="Line">
              <style-length-input adaptive="true" key="lineHeight"/>
            </field>
            <field label="Color">
              <color-input key="color"/>
            </field>
            <field label="Font">
              <font-input key="fontFamily"/>
            </field>
            <field label="Alignment">
              <segmented key="textAlign" allow-null="true">
                <option icon="style/text_align_left" value="left"/>
                <option icon="style/text_align_center" value="center"/>
                <option icon="style/text_align_right" value="right"/>
                <option icon="style/text_align_justify" value="justify"/>
              </segmented>
            </field>
            <field label="Weight">
              <segmented allow-null="true" key="fontWeight">
                <option icon="style/text_bold" value="bold"/>
                <option icon="style/text_normal" value="normal"/>
                <option text="300" value="300"/>
                <option text="200" value="200"/>
                <option text="100" value="100"/>
              </segmented>
            </field>
            </fields>
          </formula>
        '
      ]
    ];
  }

  function getToolbars() {
    return [
      GuiUtils::getTranslated(['Header', 'da'=>'Overskrift']) =>
      '
      <item label="{Level; da:Niveau}">
        <segmented name="level">
          <option text="1" value="1"/>
          <option text="2" value="2"/>
          <option text="3" value="3"/>
          <option text="4" value="4"/>
          <option text="5" value="5"/>
          <option text="6" value="6"/>
        </segmented>
      </item>
      <item label="{Size; da:Størrelse}">
        <style-length-input name="fontSize" width="90"/>
      </item>
      <item label="{Alignment; da:Justering}">
        <segmented name="textAlign" allow-null="true">
          <option icon="style/text_align_left" value="left"/>
          <option icon="style/text_align_center" value="center"/>
          <option icon="style/text_align_right" value="right"/>
          <option icon="style/text_align_justify" value="justify"/>
        </segmented>
      </item>
      <divider/>
      <item label="{Font; da:Skrift}">
        <font-input name="fontFamily"/>
      </item>
      <item label="{Line height; da:Linjehøjde}">
        <style-length-input name="lineHeight" width="90"/>
      </item>
      <item label="{Color; da:Farve}">
        <color-input name="color"/>
      </item>
      <item label="{Weight; da:Fed}">
        <segmented name="fontWeight" allow-null="true">
          <option icon="style/text_normal" value="normal"/>
          <option icon="style/text_bold" value="bold"/>
        </segmented>
      </item>
      <item label="{Italic; da:Kursiv}">
        <segmented name="fontStyle" allow-null="true">
          <option icon="style/text_normal" value="normal"/>
          <option icon="style/text_italic" value="italic"/>
        </segmented>
      </item>
      ',
    GuiUtils::getTranslated(['Advanced', 'da'=>'Avanceret']) =>
      '
      <item label="{Word spacing; da:Ord-mellemrum}">
        <style-length-input name="wordSpacing" width="90"/>
      </item>
      <item label="{Letter spacing; da:Tegn-mellemrum}">
        <style-length-input name="letterSpacing" width="90"/>
      </item>
      <item label="{Indentation; da:Indrykning}">
        <style-length-input name="textIndent" width="90"/>
      </item>
      <item label="{Letters; da:Bogstaver}">
        <segmented name="textTransform" allow-null="true">
          <option icon="style/text_normal" value="normal"/>
          <option icon="style/text_transform_capitalize" value="capitalize"/>
          <option icon="style/text_transform_uppercase" value="uppercase"/>
          <option icon="style/text_transform_lowercase" value="lowercase"/>
        </segmented>
      </item>
      <item label="Variant">
        <segmented name="fontVariant" allow-null="true">
          <option icon="style/font_variant_normal" value="normal"/>
          <option icon="style/font_variant_smallcaps" value="small-caps"/>
        </segmented>
      </item>
      <item label="{Stroke; da:Streg}">
        <segmented name="textDecoration" allow-null="true">
          <option icon="style/text_normal" value="none"/>
          <option icon="style/text_decoration_underline" value="underline"/>
          <option icon="style/text_decoration_linethrough" value="line-through"/>
          <option icon="style/text_decoration_overline" value="overline"/>
        </segmented>
      </item>
      '
    ];
  }
}
?>