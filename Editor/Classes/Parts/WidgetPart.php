<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['WidgetPart'] = [
  'table' => 'part_widget',
  'identity' => 'part_id',
  'properties' => [
    'key' => [ 'type' => 'string' ],
    'data' => [ 'type' => 'string' ]
  ]
];

class WidgetPart extends Part
{
  var $key;
  var $data;

  function __construct() {
    parent::__construct('widget');
  }

  static function load($id) {
    return Part::get('widget',$id);
  }

  function setKey($key) {
    $this->key = $key;
  }

  function getKey() {
    return $this->key;
  }

  function setData($data) {
    $this->data = $data;
  }

  function getData() {
    return $this->data;
  }

}
?>