<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['TablePart'] = [
  'table' => 'part_table',
  'identity' => 'part_id',
  'properties' => [
    'html' => [ 'type' => 'string' ]
  ]
];

class TablePart extends Part
{
  var $html;

  function __construct() {
    parent::__construct('table');
  }

  static function load($id) {
    return Part::get('table',$id);
  }

  function setHtml($html) {
    $this->html = $html;
  }

  function getHtml() {
    return $this->html;
  }

}
?>