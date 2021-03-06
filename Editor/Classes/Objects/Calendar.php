<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Calendar'] = [
    'table' => 'calendar',
    'properties' => []
];
class Calendar extends ModelObject {

  function __construct() {
    parent::__construct('calendar');
  }

  static function load($id) {
    return ModelObject::get($id,'calendar');
  }

  function removeMore() {
    $sql = "delete from calendar_event where calendar_id=@int(id)";
    Database::delete($sql, ['id' => $this->id]);
  }

  function getIcon() {
    return 'common/calendar';
  }
}
?>