<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Objects
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}
Entity::$schema['Design'] = [
    'table' => 'design',
    'properties' => [
      'unique' => ['type' => 'string'],
      'parameters' => ['type' => 'string']
    ]
];

class Design extends Object {

  var $unique;
  var $parameters;

  function Design() {
    parent::__construct('design');
  }

  static function load($id) {
    return Object::get($id,'design');
  }

  function setUnique($unique) {
    $this->unique = $unique;
  }

  function getUnique() {
    return $this->unique;
  }

  function setParameters($parameters) {
    $this->parameters = $parameters;
  }

  function getParameters() {
    return $this->parameters;
  }

  function getIcon() {
    return 'common/color';
  }

    //////////////////// Special ////////////////////

  function canRemove() {
    $sql = "select count(id) as num from page where design_id=@int(id)";
    if ($row = Database::selectFirst($sql, ['id' => $this->id])) {
      return $row['num'] == 0;
    }
    return true;
  }

  function removeMore() {
    $sql = 'delete from design_parameter where design_id=@int(id)';
    Database::delete($sql, ['id' => $this->id]);
  }
}
?>