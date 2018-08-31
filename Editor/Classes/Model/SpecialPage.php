<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Model
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}
Entity::$schema['SpecialPage'] = [
  'table' => 'specialpage',
  'properties' => [
    'id' => ['type' => 'int'],
    'language' => ['type' => 'string'],
    'type' => ['type' => 'string'],
    'pageId' => ['type' => 'int', 'column' => 'page_id', 'relation' => ['class' => 'Page', 'property' => 'id']]
  ]
];
class SpecialPage extends Entity {

  var $pageId;
  var $language;
  var $type;

  function setPageId($pageId) {
    $this->pageId = $pageId;
  }

  function getPageId() {
    return $this->pageId;
  }

  function setLanguage($language) {
    $this->language = $language;
  }

  function getLanguage() {
    return $this->language;
  }

  function setType($type) {
    $this->type = $type;
  }

  function getType() {
    return $this->type;
  }

  static function search() {
    $list = [];

    $sql = "select * from specialpage order by `type`,language,id";
    $result = Database::select($sql);
    while ($row = Database::next($result)) {
      $item = new SpecialPage();
      $item->setId($row['id']);
      $item->setPageId(intval($row['page_id']));
      $item->setType($row['type']);
      $item->setLanguage($row['language']);
      $list[] = $item;
    }
    Database::free($result);
    return $list;
  }


  static function load($id) {
    return ModelService::load('SpecialPage', $id);
  }

  function remove() {
    ModelService::remove($this);
    EventService::fireEvent('delete','specialpage',null,$this->id);
  }

  function save() {
    ModelService::save($this);
    EventService::fireEvent($this->id > 0 ? 'update' : 'create','specialpage',null,$this->id);
  }
}