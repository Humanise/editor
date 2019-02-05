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
    'id' => ['type' => 'int'],
    'objectId' => ['type' => 'int', 'column' => 'object_id', 'relation' => ['class' => 'Object', 'property' => 'id']],
    'text' => ['type' => 'string', 'column' => 'title'],
    'alternative' => ['type' => 'string'],
    'target' => ['type' => 'string'],
    'type' => ['type' => 'string', 'column' => 'target_type'],
    'position' => ['type' => 'int'],
    'value' => ['type' => 'string', 'column' => 'target_value', 'relations' => [
        ['class' => 'Page', 'property' => 'id'],
        ['class' => 'File', 'property' => 'id']
      ]
    ]
  ]
];
class ObjectLink extends Entity {

  var $type;
  var $alternative;
  var $target;
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

  function setAlternative($alternative) {
    $this->alternative = $alternative;
  }

  function getAlternative() {
    return $this->alternative;
  }

  function setTarget($target) {
    $this->target = $target;
  }

  function getTarget() {
    return $this->target;
  }

  // Special...

  static $icons = ['file' => 'monochrome/file', 'page' => 'common/page', 'url' => 'monochrome/globe', 'email' => 'monochrome/email'];

  function getIcon() {
    return ObjectLink::$icons[$this->type];
  }

  function search($query = []) {
    return ObjectLinkService::search($query);
  }

  function save() {
    ModelService::save($this);
  }

  static function load($id) {
    return ModelService::load('ObjectLink', $id);
  }

  function remove() {
    ModelService::remove($this);
  }
}