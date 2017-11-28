<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Templates
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}
class AuthenticationTemplate {

  var $id;
  var $title;

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

  function save() {
    $sql = "update authentication set title = @text(title) where page_id = @int(pageId)";
    Database::update($sql, ['pageId' => $page->id, 'title' => $this->title]);
    PageService::markChanged($this->id);
  }

  static function load($id) {
    $sql = "select title,page_id from authentication where page_id = @id";
    if ($row = Database::selectFirst($sql, $id)) {
      $obj = new AuthenticationTemplate();
      $obj->setId(intval($row['page_id']));
      $obj->setTitle($row['title']);
      return $obj;
    }
    return null;
  }
}