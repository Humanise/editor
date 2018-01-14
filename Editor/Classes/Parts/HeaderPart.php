<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['HeaderPart'] = [
  'table' => 'part_header',
  'identity' => 'part_id',
  'properties' => [
    'text' => ['type' => 'string'],
    'level' => ['type' => 'int' , 'column' => 'level'],
    'textAlign' => [ 'type' => 'string', 'column' => 'textalign' ],
    'fontFamily' => [ 'type' => 'string', 'column' => 'fontfamily' ],
    'fontSize' => [ 'type' => 'string', 'column' => 'fontsize' ],
    'lineHeight' => [ 'type' => 'string', 'column' => 'lineheight' ],
    'fontWeight' => [ 'type' => 'string', 'column' => 'fontweight' ],
    'color' => [ 'type' => 'string', 'column' => 'color' ],
    'wordSpacing' => [ 'type' => 'string', 'column' => 'wordspacing' ],
    'letterSpacing' => [ 'type' => 'string', 'column' => 'letterspacing' ],
    'textDecoration' => [ 'type' => 'string', 'column' => 'textdecoration' ],
    'textIndent' => [ 'type' => 'string', 'column' => 'textindent' ],
    'textTransform' => [ 'type' => 'string', 'column' => 'texttransform' ],
    'fontStyle' => [ 'type' => 'string', 'column' => 'fontstyle' ],
    'fontVariant' => [ 'type' => 'string', 'column' => 'fontvariant' ]
  ]
];
class HeaderPart extends Part
{
  var $text;
  var $level;
  var $textAlign;
  var $fontFamily;
  var $fontSize;
  var $lineHeight;
  var $fontWeight;
  var $color;
  var $wordSpacing;
  var $letterSpacing;
  var $textDecoration;
  var $textIndent;
  var $textTransform;
  var $fontStyle;
  var $fontVariant;

  function HeaderPart() {
    parent::Part('header');
  }

  static function load($id) {
    return Part::get('header',$id);
  }

  function setText($text) {
    $this->text = $text;
  }

  function getText() {
      return $this->text;
  }

  function setLevel($level) {
    $this->level = $level;
  }

  function getLevel() {
    return $this->level;
  }

  function setTextAlign($textAlign) {
    $this->textAlign = $textAlign;
  }

  function getTextAlign() {
      return $this->textAlign;
  }

  function setFontFamily($fontFamily) {
    $this->fontFamily = $fontFamily;
  }

  function getFontFamily() {
      return $this->fontFamily;
  }

  function setFontSize($fontSize) {
    $this->fontSize = $fontSize;
  }

  function getFontSize() {
      return $this->fontSize;
  }

  function setLineHeight($lineHeight) {
    $this->lineHeight = $lineHeight;
  }

  function getLineHeight() {
      return $this->lineHeight;
  }

  function setFontWeight($fontWeight) {
    $this->fontWeight = $fontWeight;
  }

  function getFontWeight() {
      return $this->fontWeight;
  }

  function setColor($color) {
    $this->color = $color;
  }

  function getColor() {
      return $this->color;
  }

  function setWordSpacing($wordSpacing) {
    $this->wordSpacing = $wordSpacing;
  }

  function getWordSpacing() {
      return $this->wordSpacing;
  }

  function setLetterSpacing($letterSpacing) {
    $this->letterSpacing = $letterSpacing;
  }

  function getLetterSpacing() {
      return $this->letterSpacing;
  }

  function setTextDecoration($textDecoration) {
    $this->textDecoration = $textDecoration;
  }

  function getTextDecoration() {
      return $this->textDecoration;
  }

  function setTextIndent($textIndent) {
    $this->textIndent = $textIndent;
  }

  function getTextIndent() {
      return $this->textIndent;
  }

  function setTextTransform($textTransform) {
    $this->textTransform = $textTransform;
  }

  function getTextTransform() {
      return $this->textTransform;
  }

  function setFontStyle($fontStyle) {
    $this->fontStyle = $fontStyle;
  }

  function getFontStyle() {
      return $this->fontStyle;
  }

  function setFontVariant($fontVariant) {
    $this->fontVariant = $fontVariant;
  }

  function getFontVariant() {
    return $this->fontVariant;
  }

}
?>