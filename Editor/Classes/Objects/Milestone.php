<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Milestone'] = [
  'table' => 'milestone',
  'properties' => [
    'deadline' => ['type' => 'datetime'],
    'completed' => ['type' => 'boolean'],
    'containingObjectId' => ['type' => 'int', 'column' => 'containing_object_id']
  ]
];

class Milestone extends ModelObject {
  var $deadline;
  var $containingObjectId;
  var $completed;

  function __construct() {
    parent::__construct('milestone');
  }

  static function load($id) {
    return ModelObject::get($id,'milestone');
  }

  function setDeadline($deadline) {
    $this->deadline = $deadline;
  }

  function getDeadline() {
    return $this->deadline;
  }

  function setContainingObjectId($id) {
    $this->containingObjectId = $id;
  }

  function getContainingObjectId() {
    return $this->containingObjectId;
  }

  function setCompleted($completed) {
    $this->completed = $completed;
  }

  function getCompleted() {
    return $this->completed;
  }

    /////////////////////////// Persistence ////////////////////////

  function _fixOptions(&$arr) {
    if (!is_array($arr)) $arr = [];
    if (!isset($arr['sort'])) $arr['sort'] = 'title';
    if (!isset($arr['project'])) $arr['project'] = 0;
    if (!isset($arr['projects']) || !is_array($arr['projects'])) {
      $arr['projects'] = [];
    }
  }

  function search($options = null) {
    Milestone::_fixOptions($options);
    $sql = "select object.id from milestone,object where object.id=milestone.object_id";
    if ($options['project'] > 0) {
      $sql .= " and containing_object_id = @int(project)";
    } elseif (count($options['projects']) > 0) {
      $sql .= " and containing_object_id in @ints(projects)";
    }
    if (isset($options['completed'])) {
      $sql .= " and milestone.completed = @boolean(completed)";
    }
    if ($options['sort'] == 'deadline') {
      $sql .= ' order by deadline';
    } else {
      $sql .= ' order by object.title';
    }
    $result = Database::select($sql, $options);
    $ids = [];
    while ($row = Database::next($result)) {
      $ids[] = $row['id'];
    }
    Database::free($result);

    $list = [];
    foreach ($ids as $id) {
      $list[] = Milestone::load($id);
    }
    return $list;
  }

  function removeMore() {
    $sql = "update task set milestone_id=0 where milestone_id = @id";
    Database::update($sql, $this->id);
  }

  ////////////////////// Convenience //////////////////////


  function getTasks() {
    $output = [];
    $sql = "select object_id from task,object where task.object_id = object.id and task.milestone_id = @id order by object.title";
    $result = Database::select($sql, $this->id);
    while ($row = Database::next($result)) {
        $output[] = Task::load($row['object_id']);
    }
    Database::free($result);
    return $output;
  }

  function getProblems() {
    $output = [];
    $sql = "select object_id from problem,object where problem.object_id = object.id and problem.milestone_id = @id order by object.title";
    $result = Database::select($sql, $this->id);
    while ($row = Database::next($result)) {
        $output[] = Problem::load($row['object_id']);
    }
    Database::free($result);
    return $output;
  }

  function getCompletedInfo() {
    $output = ['completed' => 0, 'active' => 0];
    $sql = "select count(object_id)-sum(problem.completed) as active,sum(problem.completed) as completed from problem where problem.milestone_id = @id union select count(object_id)-sum(task.completed) as active,sum(task.completed) as completed from task where task.milestone_id = @id";
    $result = Database::select($sql, $this->id);
    while ($row = Database::next($result)) {
        $output['completed'] += $row['completed'];
        $output['active'] += $row['active'];
    }
    Database::free($result);
    return $output;
  }

  /////////////////////////// GUI /////////////////////////

  function getIcon() {
      return 'common/time';
  }
}
?>