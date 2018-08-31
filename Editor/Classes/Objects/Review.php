<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Objects
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Review'] = [
  'table' => 'review',
  'properties' => [
    'accepted' => ['type' => 'boolean'],
    'date' => ['type' => 'datetime']
  ]
];

class Review extends ModelObject {

  var $accepted;
  var $date;

  function __construct() {
    parent::__construct('review');
  }

  static function load($id) {
    return ModelObject::get($id,'review');
  }

  function setAccepted($accepted) {
    $this->accepted = $accepted;
  }

  function getAccepted() {
    return $this->accepted;
  }

  function setDate($date) {
    $this->date = $date;
  }

  function getDate() {
    return $this->date;
  }

}
?>