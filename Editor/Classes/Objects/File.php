<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
*/

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['File'] = [
  'table' => 'file',
  'properties' => [
    'filename' => ['type' => 'string'],
    'size' => ['type' => 'int'],
    'mimetype' => ['type' => 'string', 'column' => 'type']
  ]
];

class File extends Object {
  var $filename;
  var $size;
  var $mimetype;

  function File() {
    parent::__construct('file');
  }

  function getIcon() {
    return "file/generic";
  }

  static function load($id) {
    return Object::get($id,'file');
  }

  function setFilename($filename) {
    $this->filename = $filename;
  }

  function getFilename() {
    return $this->filename;
  }

  function setSize($size) {
    $this->size = $size;
  }

  function getSize() {
    return $this->size;
  }

  function setMimetype($type) {
    $this->mimetype = $type;
  }

  function getMimetype() {
    return $this->mimetype;
  }

  function sub_publish() {
    $data =
    '<file xmlns="' . parent::_buildnamespace('1.0') . '">' .
    '<filename>' . Strings::escapeEncodedXML($this->filename) . '</filename>' .
    '<size>' . Strings::escapeEncodedXML($this->size) . '</size>' .
    '<mimetype>' . Strings::escapeEncodedXML($this->mimetype) . '</mimetype>' .
    '</file>';
    return $data;
  }

  function removeMore() {
    global $basePath;
    $path = $basePath . 'files/' . $this->filename;
    if (file_exists($path)) {
      !@unlink ($path);
    }
    $sql = "delete from filegroup_file where file_id = @int(id)";
    Database::delete($sql, ['id' => $this->id]);
  }

  /***************** Groups ****************/

  function getGroupIds() {
    $sql = "select filegroup_id as id from filegroup_file where file_id = @int(id)";
    return Database::getIds($sql, ['id' => $this->id]);
  }

  function updateGroupIds($ids) {
    $ids = ObjectService::getValidIds($ids);
    $sql = "delete from filegroup_file where file_id=@int(id)";
    Database::delete($sql, ['id' => $this->id]);
    foreach ($ids as $id) {
      $sql = "insert into filegroup_file (filegroup_id,file_id) values (@int(groupId), @int(fileId))";
      Database::insert($sql, ['groupId' => $id, 'fileId' => $this->id]);
    }
  }

  function addGroupId($id) {
    $sql = "delete from filegroup_file where file_id=@int(fileId) and filegroup_id=@int(groupId)";
    Database::delete($sql, ['groupId' => $id, 'fileId' => $this->id]);
    $sql = "insert into filegroup_file (filegroup_id,file_id) values (@int(groupId),@int(fileId))";
    Database::insert($sql, ['groupId' => $id, 'fileId' => $this->id]);
  }

  /********************** Search *****************/


  function addCustomSearch($query,&$parts) {
    $custom = $query->getCustom();
    if (isset($custom['group'])) {
      $parts['tables'][] = 'filegroup_file';
      $parts['limits'][] = 'filegroup_file.file_id=object.id';
      $parts['limits'][] = 'filegroup_file.filegroup_id=' . Database::int($custom['group']);
    }
    if (isset($custom['mimetype']) && is_array($custom['mimetype'])) {
      $ors = [];
      foreach ($custom['mimetype'] as $type) {
        $ors[] = '`file`.`type` = ' . Database::text($type);
      }
      $parts['limits'][] = '(' . implode(' or ',$ors) . ')';
    }
  }

  static function getTypeCounts() {
    $sql = "select type,count(object_id) as count from file group by type order by type";
    return Database::selectAll($sql);
  }

}
?>