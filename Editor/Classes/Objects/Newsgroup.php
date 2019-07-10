<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Newsgroup'] = [
  'table' => 'newsgroup',
  'properties' => []
];

class Newsgroup extends ModelObject {

  function __construct() {
    parent::__construct('newsgroup');
  }

  static function load($id) {
    return ModelObject::get($id,'newsgroup');
  }

  function removeMore() {
    $sql = "delete from newsgroup_news where newsgroup_id = @id";
    Database::delete($sql, $this->id);
    $sql = "delete from part_news_newsgroup where newsgroup_id = @id";
    Database::delete($sql, $this->id);
  }

  function getIcon() {
    return "common/folder";
  }
}
?>