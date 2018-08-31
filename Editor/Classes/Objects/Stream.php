<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Objects
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Stream'] = [
  'table' => 'stream',
  'properties' => [
  ]
];

class Stream extends ModelObject {

  function __construct() {
    parent::__construct('stream');
  }

  function getIcon() {
    return 'common/water';
  }

  static function load($id) {
    return ModelObject::get($id,'stream');
  }

  function removeMore() {
    $items = Query::after('streamitem')->withProperty('stream_id',$this->id)->get();
    foreach ($items as $item) {
      $item->remove();
    }
  }

}