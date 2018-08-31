<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Model
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}
Entity::$schema['DocumentSection'] = [
  'table' => 'document_section',
  'properties' => [
    'id' => ['type' => 'int'],
    'pageId' => ['type' => 'int', 'column' => 'page_id', 'relation' => ['class' => 'Page', 'property' => 'id']],
    'columnId' => ['type' => 'int', 'column' => 'column_id', 'relation' => ['class' => 'DocumentColumn', 'property' => 'id']],
    'partId' => ['type' => 'int', 'column' => 'part_id'],
    'index' => ['type' => 'int'],
    'type' => ['type' => 'string'],
    'top' => ['type' => 'string'],
    'bottom' => ['type' => 'string'],
    'left' => ['type' => 'string'],
    'right' => ['type' => 'string'],
    'float' => ['type' => 'string'],
    'width' => ['type' => 'string'],
    'class' => ['type' => 'string'],
    'style' => ['type' => 'string']
  ]
];
class DocumentSection extends Entity implements Loadable {

  var $pageId;
  var $columnId;
  var $partId;
  var $index;
  var $type;
  var $top;
  var $bottom;
  var $left;
  var $right;
  var $float;
  var $width;
  var $class;
  var $style;

  function setPageId($pageId) {
      $this->pageId = $pageId;
  }

  function getPageId() {
      return $this->pageId;
  }

  function setColumnId($columnId) {
      $this->columnId = $columnId;
  }

  function getColumnId() {
      return $this->columnId;
  }

  function setPartId($partId) {
      $this->partId = $partId;
  }

  function getPartId() {
      return $this->partId;
  }

  function setIndex($index) {
      $this->index = $index;
  }

  function getIndex() {
      return $this->index;
  }

  function setType($type) {
      $this->type = $type;
  }

  function getType() {
      return $this->type;
  }

  function setTop($top) {
      $this->top = $top;
  }

  function getTop() {
      return $this->top;
  }

  function setBottom($bottom) {
      $this->bottom = $bottom;
  }

  function getBottom() {
      return $this->bottom;
  }

  function setLeft($left) {
      $this->left = $left;
  }

  function getLeft() {
      return $this->left;
  }

  function setRight($right) {
      $this->right = $right;
  }

  function getRight() {
      return $this->right;
  }

  function setFloat($float) {
      $this->float = $float;
  }

  function getFloat() {
      return $this->float;
  }

  function setWidth($width) {
      $this->width = $width;
  }

  function getWidth() {
      return $this->width;
  }

  function setClass($class) {
      $this->class = $class;
  }

  function getClass() {
      return $this->class;
  }

  function setStyle($style) {
      $this->style = $style;
  }

  function getStyle() {
      return $this->style;
  }

  // Persistence...

  static function load($id) {
    return ModelService::load('DocumentSection', $id);
  }

  function remove() {
    ModelService::remove($this);
  }

  function save() {
    ModelService::save($this);
  }
}