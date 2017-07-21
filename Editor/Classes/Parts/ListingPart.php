<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['ListingPart'] = [
  'table' => 'part_listing',
  'properties' => [
    'text'   => ['type'=>'string'],
    'listStyle'   => ['type'=>'string' , 'column' => 'type'],
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
class ListingPart extends Part
{
  var $text;
  var $listStyle;
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

  function ListingPart() {
    parent::Part('listing');
  }

  static function load($id) {
    return Part::get('listing',$id);
  }

  function setText($text) {
    $this->text = $text;
  }

  function getText() {
    return $this->text;
  }

  function setListStyle($listStyle) {
    $this->listStyle = $listStyle;
  }

  function getListStyle() {
    return $this->listStyle;
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