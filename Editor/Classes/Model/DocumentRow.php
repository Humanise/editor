<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Model
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}
Entity::$schema['DocumentRow'] = [
  'table' => 'document_row',
  'properties' => [
    'id' => ['type' => 'int'],
    'pageId' => ['type' => 'int', 'column' => 'page_id', 'relation' => ['class' => 'Page', 'property' => 'id']],
    'index' => ['type' => 'int'],
    'top' => ['type' => 'string'],
    'bottom' => ['type' => 'string'],
    'spacing' => ['type' => 'string'],
    'layout' => ['type' => 'string'],
    'class' => ['type' => 'string'],
    'style' => ['type' => 'string']
  ]
];

class DocumentRow extends Entity implements Loadable {

  var $pageId;
  var $index;
  var $top;
  var $bottom;
  var $spacing;
  var $layout;
  var $class;
  var $style;

  function setPageId($pageId) {
    $this->pageId = $pageId;
  }

  function getPageId() {
    return $this->pageId;
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

  function setSpacing($spacing) {
    $this->spacing = $spacing;
  }

  function getSpacing() {
    return $this->spacing;
  }

  function setLayout($layout) {
    $this->layout = $layout;
  }

  function getLayout() {
    return $this->layout;
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
    return ModelService::load('DocumentRow', $id);
  }

  function remove() {
    ModelService::remove($this);
  }

  function save() {
    ModelService::save($this);
  }
}