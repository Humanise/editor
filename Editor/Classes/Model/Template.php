<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Template'] = [
  'table' => 'template',
  'properties' => [
    'id' => ['type' => 'int'],
    'unique' => ['type' => 'string']
  ]
];
class Template extends Entity {

  var $unique;

  function setUnique($unique) {
    $this->unique = $unique;
  }

  function getUnique() {
    return $this->unique;
  }

  function getName() {
    $info = TemplateService::getTemplateInfo($this->unique);
    return $info['name'];
  }

  static function load($id) {
    return ModelService::load('Template', $id);
  }

  static function loadByUnique($unique) {
    return TemplateService::getTemplateByUnique($unique);
  }

  function remove() {
    ModelService::remove($this);
  }

  function save() {
    ModelService::save($this);
  }
}