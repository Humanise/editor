<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Objects
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Newssourceitem'] = [
    'table' => 'newssourceitem',
    'properties' => [
      'text' => ['type'=>'string'],
      'url' => ['type'=>'string'],
      'newssourceId' => ['type' => 'int', 'column' => 'newssource_id'],
      'date' => ['type'=>'datetime'],
      'guid' => ['type'=>'string']
    ]
];

class Newssourceitem extends Object {
  var $text;
  var $url;
  var $date;
  var $guid;
  var $newssourceId;

  function Newssourceitem() {
    parent::Object('newssourceitem');
  }

  static function load($id) {
    return Object::get($id,'newssourceitem');
  }

  function setText($text) {
      $this->text = $text;
  }

  function getText() {
      return $this->text;
  }

  function setNewssourceId($newssourceId) {
      $this->newssourceId = $newssourceId;
  }

  function getNewssourceId() {
      return $this->newssourceId;
  }


  function setUrl($url) {
      $this->url = $url;
  }

  function getUrl() {
      return $this->url;
  }

  function setDate($date) {
      $this->date = $date;
  }

  function getDate() {
      return $this->date;
  }

  function setGuid($guid) {
      $this->guid = $guid;
  }

  function getGuid() {
      return $this->guid;
  }


  function getIcon() {
    return 'file/generic';
  }

  function addCustomSearch($query,&$parts) {
    $custom = $query->getCustom();
    if (isset($custom['minDate'])) {
      $parts['limits'][] = 'newssourceitem.date>='.Database::date($custom['minDate']);
    }
  }
}