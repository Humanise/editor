<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Remotepublisher'] = [
  'table' => 'remotepublisher',
  'properties' => [
      'url' => ['type' => 'string'],
  ]
];

class Remotepublisher extends ModelObject {
  var $url;

  function __construct() {
    parent::__construct('remotepublisher');
  }

  static function load($id) {
    return ModelObject::get($id,'remotepublisher');
  }

  function setUrl($url) {
    $this->url = $url;
  }

  function getUrl() {
    return $this->url;
  }

}
?>