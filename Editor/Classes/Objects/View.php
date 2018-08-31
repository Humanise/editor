<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Objects
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['View'] = [
  'table' => 'view',
  'properties' => [
    'path' => ['type' => 'string']
  ]
];

class View extends ModelObject {

  var $path;

  function __construct() {
    parent::__construct('view');
  }

  static function load($id) {
    return ModelObject::get($id,'view');
  }

  function setPath($path) {
    $this->path = $path;
  }

  function getPath() {
    return $this->path;
  }

}