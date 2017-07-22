<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}


Entity::$schema['ObjectLink'] = [
  'table' => 'object_link',
  'properties' => [
    'objectId' => ['type' => 'int', 'relation' => ['class' => 'Object', 'property' => 'id']],
    'value' => ['type' => 'int', 'relations' => [
      ['class' => 'Page', 'property' => 'id'],
      ['class' => 'File', 'property' => 'id']
      ]
    ]
  ]
];
class ObjectLink extends Entity {

  var $type;
  var $value;
  var $position;
  var $text;
  var $objectId;
  var $info;

  function setInfo($info) {
    $this->info = $info;
  }

  function getInfo() {
    return $this->info;
  }


  function setType($type) {
    $this->type = $type;
  }

  function getType() {
    return $this->type;
  }

  function setValue($value) {
    $this->value = $value;
  }

  function getValue() {
    return $this->value;
  }

  function setPosition($position) {
    $this->position = $position;
  }

  function getPosition() {
    return $this->position;
  }

  function setText($text) {
    $this->text = $text;
  }

  function getText() {
    return $this->text;
  }

  function setObjectId($objectId) {
    $this->objectId = $objectId;
  }

  function getObjectId() {
    return $this->objectId;
  }

  static $icons = ['file' => 'monochrome/file', 'page' => 'common/page', 'url' => 'monochrome/globe', 'email' => 'monochrome/email'];

  function getIcon() {
    return ObjectLink::$icons[$this->type];
  }

  function search($query = []) {
    return ObjectLinkService::search($query);
  }
}