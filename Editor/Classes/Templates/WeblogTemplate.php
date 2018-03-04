<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Templates
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}
class WeblogTemplate {

  var $id;
  var $title;
  var $pageBlueprintId;
  var $groupIds;

  function setId($id) {
    $this->id = $id;
  }

  function getId() {
    return $this->id;
  }

  function setTitle($title) {
    $this->title = $title;
  }

  function getTitle() {
    return $this->title;
  }

  function setGroupIds($groups) {
    $this->groupIds = $groups;
  }

  function getGroupIds() {
    return $this->groupIds;
  }

  function setPageBlueprintId($pageBlueprintId) {
    $this->pageBlueprintId = $pageBlueprintId;
  }

  function getPageBlueprintId() {
    return $this->pageBlueprintId;
  }

  function save() {
    $sql = "update weblog set pageblueprint_id = @int(blueprint), title = @text(title) where page_id = @id";
    Database::update($sql, [
      'blueprint' => $this->pageBlueprintId,
      'title' => $this->title,
      'id' => $this->id
    ]);

    $sql = "delete from weblog_webloggroup where page_id = @id";
    Database::delete($sql, $this->id);

    foreach ($this->groupIds as $group) {
      $sql = "insert into weblog_webloggroup (page_id,webloggroup_id) values (@id, @int(group))";
      Database::insert($sql, [
        'id' => $this->id,
        'group' => $group
      ]);
    }
    PageService::markChanged($this->id);
  }

  static function load($id) {
    $sql = "select * from weblog where page_id = @id";
    if ($row = Database::selectFirst($sql, $id)) {
      $obj = new WeblogTemplate();
      $obj->setId(intval($row['page_id']));
      $obj->setTitle($row['title']);
      $obj->setPageBlueprintId(intval($row['pageblueprint_id']));

      $sql = "select webloggroup_id as id from weblog_webloggroup where page_id = @id";
      $groups = Database::selectIntArray($sql, $id);
      $obj->setGroupIds($groups);

      return $obj;
    }
    return null;
  }
}