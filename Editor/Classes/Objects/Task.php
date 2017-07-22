<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Task'] = [
  'table' => 'task',
  'properties' => [
      'deadline' => ['type' => 'datetime'],
      'completed' => ['type' => 'boolean'],
      'containingObjectId' => ['type' => 'int', 'column' => 'containing_object_id'],
      'milestoneId' => ['type' => 'int', 'column' => 'milestone_id'],
      'priority' => ['type' => 'float']
  ]
];

class Task extends Object {

  var $deadline;
  var $completed = false;
  var $containingObjectId = 0;
  var $milestoneId = 0;
  var $priority;

  function Task() {
    parent::Object('task');
  }

  static function load($id) {
    return Object::get($id,'task');
  }

  function setDeadline($deadline) {
    $this->deadline = $deadline;
  }

  function getDeadline() {
    return $this->deadline;
  }

  function setCompleted($completed) {
    $this->completed = $completed;
  }

  function getCompleted() {
    return $this->completed;
  }

  function setContainingObjectId($id) {
    $this->containingObjectId = $id;
  }

  function getContainingObjectId() {
    return $this->containingObjectId;
  }

  function setMilestoneId($id) {
    $this->milestoneId = $id;
  }

  function getMilestoneId() {
    return $this->milestoneId;
  }

  function setPriority($priority) {
      $this->priority = $priority;
  }

  function getPriority() {
      return $this->priority;
  }

  function sub_publish() {
    $data =
    '<task xmlns="' . parent::_buildnamespace('1.0') . '">' .
    '</task>';
    return $data;
  }
}
?>