<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Productgroup'] = [
  'table' => 'productgroup',
  'properties' => []
];

class Productgroup extends ModelObject {

  function __construct() {
    parent::__construct('productgroup');
  }

  static function load($id) {
    return ModelObject::get($id,'productgroup');
  }

  function removeMore() {
    $sql = "delete from productgroup_product where productgroup_id=@int(id)";
    Database::delete($sql, ['id' => $this->id]);
  }

  /////////////////////////// GUI /////////////////////////

  function getIcon() {
      return 'common/folder';
  }
}
?>