<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Objects
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Issue'] = [
  'table' => 'issue',
  'properties' => [
    'kind' => ['type' => 'string'],
    'statusId' => ['type' => 'int', 'column' => 'issuestatus_id', 'relation' => ['class' => 'Issuestatus', 'property' => 'id']]
  ]
];

class Issue extends Object {

  static $unknown = 'unknown';
  static $improvement = 'improvement';
  static $task = 'task';
  static $feedback = 'feedback';
  static $error = 'error';

  var $kind;
  var $statusId;

  function Issue() {
    parent::__construct('issue');
  }

  static function load($id) {
    return Object::get($id,'issue');
  }

  function setKind($kind) {
    $this->kind = $kind;
  }

  function getKind() {
    return $this->kind;
  }

  function setStatusId($statusId) {
    $this->statusId = $statusId;
  }

  function getStatusId() {
    return $this->statusId;
  }


  function getIcon() {
    return 'file/generic';
  }
}
?>