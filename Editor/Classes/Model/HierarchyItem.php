<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Model
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['HierarchyItem'] = [
  'table' => 'hierarchy_item',
  'properties' => [
    'id' => ['type' => 'int'],
    'title' => ['type' => 'string'],
    'hidden' => ['type' => 'boolean'],
    'targetType' => ['type' => 'string'],
    'parent' => ['type' => 'int'],
    'index' => ['type' => 'int'],
    'targetValue' => ['type' => 'int', 'relations' => [
      ['class' => 'Page', 'property' => 'id'],
      ['class' => 'File', 'property' => 'id']
      ]
    ]
  ]
];
class HierarchyItem extends Entity implements Loadable {

  var $title;
  var $hidden;
  var $canDelete;
  var $targetType;
  var $targetValue;
  var $hierarchyId;
  var $parent;
  var $index;

  function __construct() {
  }

  function setTitle($title) {
    $this->title = $title;
  }

  function getTitle() {
    return $this->title;
  }

  function setHierarchyId($hierarchyId) {
    $this->hierarchyId = $hierarchyId;
  }

  function getHierarchyId() {
    return $this->hierarchyId;
  }

  function setParent($parent) {
    $this->parent = $parent;
  }

  function getParent() {
    return $this->parent;
  }

  function setIndex($index) {
    $this->index = $index;
  }

  function getIndex() {
    return $this->index;
  }


  function setHidden($hidden) {
    $this->hidden = $hidden;
  }

  function getHidden() {
    return $this->hidden;
  }

  function setCanDelete($canDelete) {
    $this->canDelete = $canDelete;
  }

  function getCanDelete() {
    return $this->canDelete;
  }

  function setTargetType($targetType) {
    $this->targetType = $targetType;
  }

  function getTargetType() {
    return $this->targetType;
  }

  function setTargetValue($targetValue) {
    $this->targetValue = $targetValue;
  }

  function getTargetValue() {
    return $this->targetValue;
  }

  static function load($id) {
    return HierarchyService::loadItem($id);
  }

  static function loadByPageId($id) {
    return HierarchyService::getItemByPageId($id);
  }

  function save() {
    $success = false;
    if ($this->id > 0) {
      $target_value = null;
      $target_id = null;
      if ($this->targetType == 'page' || $this->targetType == 'pageref' || $this->targetType == 'file') {
        $target_id = $this->targetValue;
      } else {
        $target_value = $this->targetValue;
      }

      $sql = [
        'table' => 'hierarchy_item',
        'values' => [
          'title' => ['text' => $this->title],
          'hidden' => ['boolean' => $this->hidden],
          'target_type' => ['text' => $this->targetType],
          'target_value' => ['text' => $target_value],
          'target_id' => ['text' => $target_id],
          'hierarchy_id' => ['int' => $this->hierarchyId],
          'parent' => ['int' => $this->parent],
          'index' => ['int' => $this->index]
        ],
        'where' => [ 'id' => ['int' => $this->id] ]
      ];

      $success = Database::update($sql);
      Hierarchy::markHierarchyOfItemChanged($this->id);
      EventService::fireEvent('update','hierarchy',null,$this->hierarchyId);
    }
    return $success;
  }
}