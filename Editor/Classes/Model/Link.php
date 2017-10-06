<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Model
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Link'] = [
  'table' => 'link',
  'properties' => [
    'id' => ['type' => 'int'],
    'text' => ['type' => 'string', 'column' => 'source_text'],
    'sourceType' => ['type' => 'string', 'column' => 'source_type'],
    'alternative' => ['type' => 'string'],
    'targetType' => ['type' => 'string', 'column' => 'target_type'],
    'targetValue' => ['type' => 'string', 'column' => 'target_value'],
    'partId' => ['type' => 'int', 'column' => 'part_id', 'relation' => ['class' => 'Part', 'property' => 'id']],
    'pageId' => ['type' => 'int', 'column' => 'page_id', 'relation' => ['class' => 'Page', 'property' => 'id']],
    'targetId' => ['type' => 'int', 'column' => 'target_id', 'relations' => [
        ['class' => 'Page', 'property' => 'id'],
        ['class' => 'File', 'property' => 'id']
      ]
    ]
  ]
];
class Link extends Entity implements Loadable {

  var $sourceType;
  var $text;
  var $alternative;
  var $targetType;
  var $targetValue;
  var $targetId;
  var $pageId;
  var $partId;

  function Link() {

  }

  function setPageId($pageId) {
    $this->pageId = $pageId;
  }

  function getPageId() {
    return $this->pageId;
  }

  function setPartId($partId) {
    $this->partId = $partId;
  }

  function getPartId() {
    return $this->partId;
  }

  function getSourceType() {
    return $this->sourceType;
  }

  function setSourceType($type) {
    $this->sourceType = $type;
  }

  function getTargetType() {
    return $this->targetType;
  }

  function setTargetType($type) {
    $this->targetType = $type;
  }

  function getTargetValue() {
    return $this->targetValue;
  }

  function setTargetValue($value) {
    $this->targetValue = $value;
  }

  function getTargetId() {
    return $this->targetId;
  }

  function setTargetId($id) {
    $this->targetId = $id;
  }

  function setText($text) {
    $this->sourceType = 'text';
    $this->text = $text;
  }

  function getText() {
    return $this->text;
  }

  function setAlternative($alternative) {
    $this->alternative = $alternative;
  }

  function getAlternative() {
    return $this->alternative;
  }

  function setTypeAndValue($type,$value) {
    if ($type == 'page' || $type == 'file') {
      $this->targetType = $type;
      $this->targetId = intval($value);
      $this->targetValue = null;
    } else if ($type == 'url' || $type == 'email') {
      $this->targetType = $type;
      $this->targetValue = $value;
      $this->targetId = null;
    }
  }

  function getUrl() {
    if ($this->targetType == 'url') {
      return $this->targetValue;
    } else {
      return '';
    }
  }

  function getEmail() {
    if ($this->targetType == 'email') {
      return $this->targetValue;
    } else {
      return '';
    }
  }

  function getPage() {
    if ($this->targetType == 'page') {
      return $this->targetId;
    } else {
      return null;
    }
  }

  function getFile() {
    if ($this->targetType == 'file') {
      return $this->targetId;
    } else {
      return null;
    }
  }

  static function load($id) {
    return ModelService::load('Link', $id);
  }

  function remove() {
    return ModelService::remove($this);
  }

  function save() {
    ModelService::save($this);
  }

}