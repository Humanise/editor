<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class HtmlTemplate {

  var $id;
  var $title;
  var $html;
  var $valid;

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

  function setHtml($html) {
    $this->html = $html;
  }

  function getHtml() {
    return $this->html;
  }

  function setValid($valid) {
    $this->valid = $valid;
  }

  function getValid() {
    return $this->valid;
  }

  //...

  static function load($id) {
    $sql = "select page_id,title,html,valid from html where page_id = @id";
    if ($row = Database::selectFirst($sql, $id)) {
      $obj = new HtmlTemplate();
      $obj->setId(intval($row['page_id']));
      $obj->setTitle($row['title']);
      $obj->setHtml($row['html']);
      $obj->setValid(!!$row['valid']);
      return $obj;
    }
    return null;
  }

  function save() {
    $sql = "update html set valid = @boolean(valid), title = @text(title), html = @text(html) where page_id = @id";
    return Database::update($sql, [
      'valid' => DOMUtils::isValidFragment($this->html),
      'html' => $this->html,
      'title' => $this->title,
      'id' => $this->id
    ]);
  }
}