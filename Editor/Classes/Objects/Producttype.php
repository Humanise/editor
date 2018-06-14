<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Producttype'] = [
  'table' => 'producttype',
  'properties' => []
];

class Producttype extends Object {

  function Producttype() {
    parent::__construct('producttype');
  }

  static function load($id) {
    return Object::get($id,'producttype');
  }

  function getIcon() {
    return "common/folder";
  }

  function canRemove() {
    $sql = "select object_id from product where producttype_id=@int(id)";
    return Database::isEmpty($sql, ['id' => $this->id]);
  }

}
?>