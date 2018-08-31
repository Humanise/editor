<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Model
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}
Entity::$schema['DocumentColumn'] = [
  'table' => 'document_column',
  'properties' => [
    'id' => ['type' => 'int'],
    'pageId' => ['type' => 'int', 'column' => 'page_id', 'relation' => ['class' => 'Page', 'property' => 'id']],
    'rowId' => ['type' => 'int', 'column' => 'row_id'],
    'index' => ['type' => 'int'],
    'top' => ['type' => 'string'],
    'bottom' => ['type' => 'string'],
    'left' => ['type' => 'string'],
    'right' => ['type' => 'string'],
    'width' => ['type' => 'string'],
    'class' => ['type' => 'string'],
    'style' => ['type' => 'string']
  ]
];

class DocumentColumn extends Entity implements Loadable {

  var $pageId;
  var $rowId;
  var $index;
  var $top;
  var $bottom;
  var $left;
  var $right;
  var $width;
  var $class;
  var $style;

  function setPageId($pageId) {
      $this->pageId = $pageId;
  }

  function getPageId() {
      return $this->pageId;
  }

  function setRowId($rowId) {
      $this->rowId = $rowId;
  }

  function getRowId() {
      return $this->rowId;
  }

  function setIndex($index) {
      $this->index = $index;
  }

  function getIndex() {
      return $this->index;
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
    return ModelService::load('DocumentColumn', $id);
  }

  function remove() {
    ModelService::remove($this);
  }

  function save() {
    ModelService::save($this);
  }
}