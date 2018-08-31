<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Persongroup'] = [
  'table' => 'persongroup',
  'properties' => []
];

class Persongroup extends ModelObject {

  function __construct() {
    parent::__construct('persongroup');
  }

  function getIcon() {
    return 'common/folder';
  }

  static function load($id) {
    return ModelObject::get($id,'persongroup');
  }

  function removeMore() {
    $sql = "delete from persongroup_person where persongroup_id=" . Database::int($this->id);
    Database::delete($sql);
  }
}
?>