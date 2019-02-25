<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Weblogentry'] = [
  'table' => 'weblogentry',
  'properties' => [
    'text' => ['type' => 'string'],
    'date' => ['type' => 'datetime'],
    'pageId' => ['type' => 'int', 'column' => 'page_id']
  ]
];

class Weblogentry extends ModelObject {
  var $text;
  var $date;
  var $pageId;
  var $groups;

  function __construct() {
    parent::__construct('weblogentry');
  }

  static function load($id) {
    return ModelObject::get($id,'weblogentry');
  }

  function setText($text) {
    $this->text = $text;
  }

  function getText() {
    return $this->text;
  }

  function setDate($date) {
    $this->date = $date;
  }

  function getDate() {
    return $this->date;
  }

  function setPageId($pageId) {
    $this->pageId = $pageId;
  }

  function getPageId() {
    return $this->pageId;
  }

  function loadGroups() {
    $this->groups = [];
    $sql = "select object.title,object.id from webloggroup_weblogentry,object where webloggroup_weblogentry.webloggroup_id=object.id and weblogentry_id = @id order by object.title";
    $subResult = Database::select($sql, $this->id);
    while ($subRow = Database::next($subResult)) {
      $this->groups[] = $subRow['id'];
    }
    Database::free($subResult);
  }

  ////////////////////////////// Persistence ///////////////////////

  function sub_publish() {
    $data =
    '<weblogentry xmlns="' . parent::_buildnamespace('1.0') . '">' .
    Dates::buildTag('date',$this->date) .
    '<text><![CDATA[' . Strings::escapeSimpleXMLwithLineBreak($this->text,'<br/>') . ']]></text>';
    $data .= '</weblogentry>';
    return $data;
  }

  function removeMore() {
    $sql = "delete from webloggroup_weblogentry where weblogentry_id = @id";
    Database::delete($sql, $this->id);
  }

  //////////////////////////// Convenience ///////////////////////////

  function changeGroups($groups) {
    if (!is_array($groups)) {
      return;
    }
    $sql = "delete from webloggroup_weblogentry where weblogentry_id = @id";
    Database::delete($sql, $this->id);
    foreach ($groups as $id) {
      $sql = "insert into webloggroup_weblogentry (weblogentry_id,webloggroup_id) values (@int(entryId),@int(groupId))";
      Database::insert($sql, ['entryId' => $this->id, 'groupId' => $id]);
    }
  }
}
?>