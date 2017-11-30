<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Parameter'] = [
  'table' => 'parameter',
  'properties' => [
    'id' => ['type' => 'int'],
    'name' => ['type' => 'string'],
    'level' => ['type' => 'string'],
    'language' => ['type' => 'string'],
    'value' => ['type' => 'string'],
  ]
];
class Parameter extends Entity implements Loadable {

  var $name;
  var $level;
  var $language;
  var $value;

  //$domain
  //$target_id
  //$value


  function setName($name) {
    $this->name = $name;
  }

  function getName() {
    return $this->name;
  }

  function setLevel($level) {
    $this->level = $level;
  }

  function getLevel() {
    return $this->level;
  }

  function setLanguage($language) {
    $this->language = $language;
  }

  function getLanguage() {
    return $this->language;
  }

  function setValue($value) {
    $this->value = $value;
  }

  function getValue() {
    return $this->value;
  }

  static function load($id) {
    return ModelService::load('Parameter',$id);
  }

  static function loadByName($name) {
    $sql = "select id from parameter where name = @text(name)";
    if ($row = Database::selectFirst($sql, ['name' => $name])) {
      return Parameter::load(intval($row['id']));
    }
    return null
  }

  function save() {
    return ModelService::save($this);
  }

  function remove() {
    return ModelService::remove($this);
  }

}