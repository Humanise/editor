<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
*/

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Filegroup'] = [
    'table' => 'filegroup',
    'properties' => []
];

class Filegroup extends ModelObject {

  function __construct() {
    parent::__construct('filegroup');
  }

  static function load($id) {
    return ModelObject::get($id,'filegroup');
  }

  function removeMore() {
    $sql = "delete from filegroup_file where filegroup_id = @int(id)";
    Database::delete($sql, ['id' => $this->id]);
  }

  function getIcon() {
    return "common/folder";
  }
}
?>