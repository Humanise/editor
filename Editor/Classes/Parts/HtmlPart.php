<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['HtmlPart'] = [
  'table' => 'part_html',
  'identity' => 'part_id',
  'properties' => [
    'html' => [ 'type' => 'string' ]
  ]
];

class HtmlPart extends Part
{
  var $html;

  function __construct() {
    parent::__construct('html');
  }

  static function load($id) {
    return Part::get('html',$id);
  }

  function setHtml($html) {
    $this->html = $html;
  }

  function getHtml() {
    return $this->html;
  }

}
?>