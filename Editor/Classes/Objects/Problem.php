<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Problem'] = [
  'table' => 'problem',
  'properties' => [
      'deadline' => ['type' => 'datetime'],
      'completed' => ['type' => 'boolean'],
      'containingObjectId' => ['type' => 'int', 'column' => 'containing_object_id'],
      'milestoneId' => ['type' => 'int', 'column' => 'milestone_id'],
      'priority' => ['type' => 'float']
    ]
];

class Problem extends ModelObject {

  var $deadline;
  var $completed = false;
  var $containingObjectId = 0;
  var $milestoneId;
  var $priority;

  function __construct() {
    parent::__construct('problem');
  }

  static function load($id) {
    return ModelObject::get($id,'problem');
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

  function setMilestoneId($milestoneId) {
      $this->milestoneId = $milestoneId;
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
    '<problem xmlns="' . parent::_buildnamespace('1.0') . '">' .
    '</problem>';
    return $data;
  }

}
?>