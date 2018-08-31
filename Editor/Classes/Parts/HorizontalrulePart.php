<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['HorizontalrulePart'] = [
  'table' => 'part_horizontalrule',
  'identity' => 'part_id',
  'properties' => []
];

class HorizontalrulePart extends Part
{
  function __construct() {
    parent::__construct('horizontalrule');
  }

  static function load($id) {
    return Part::get('horizontalrule',$id);
  }
}
?>