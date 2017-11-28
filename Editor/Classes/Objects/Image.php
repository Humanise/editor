<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Image'] = [
  'table' => 'image',
  'properties' => [
    'filename' => ['type' => 'string'],
    'size' => ['type' => 'int'],
    'width' => ['type' => 'int'],
    'height' => ['type' => 'int'],
    'mimetype' => ['type' => 'string', 'column' => 'type']
  ]
];

class Image extends Object {

  var $filename;
  var $size;
  var $width;
  var $height;
  var $mimetype;

  function Image() {
    parent::Object('image');
  }

  static function load($id) {
    return Object::get($id,'image');
  }

  function getIcon() {
      return 'common/image';
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

  function setWidth($width) {
    $this->width = $width;
  }

  function getWidth() {
    return $this->width;
  }

  function setHeight($height) {
    $this->height = $height;
  }

  function getHeight() {
    return $this->height;
  }

  function setMimetype($type) {
    $this->mimetype = $type;
  }

  function getMimetype() {
    return $this->mimetype;
  }

  function clearCache() {
    global $basePath;
    $dir = $basePath . 'local/cache/images/';
    $files = FileSystemService::listFiles($dir);
    foreach ($files as $file) {
      if (preg_match('/' . $this->id . '[a-z_]/i',$file)) {
        @unlink($dir . $file);
      }
    }
  }

  function addGroupId($id) {
    $sql = "delete from imagegroup_image where image_id=@int(id) and imagegroup_id = @int(groupId)";
    Database::delete($sql, ['id' => $this->id, 'groupId' => $id]);
    $sql = "insert into imagegroup_image (imagegroup_id,image_id) values (@int(groupId), @int(id))";
    Database::insert($sql, ['id' => $this->id, 'groupId' => $id]);
  }

  function getGroupIds() {
    $ids = [];
    $sql = "select imagegroup_id from imagegroup_image where image_id = @int(id)";
    $result = Database::select($sql, ['id' => $this->id]);
    while ($row = Database::next($result)) {
      $ids[] = $row['imagegroup_id'];
    }
    Database::free($result);
    return $ids;
  }

  function changeGroups($groups) {
    $sql = "delete from imagegroup_image where image_id=@int(id)";
    Database::delete($sql, ['id' => $this->id]);
    foreach ($groups as $id) {
      if ($id > 0) {
        $sql = "insert into imagegroup_image (image_id,imagegroup_id) values (@int(id), @int(groupId))";
        Database::insert($sql, ['id' => $this->id, 'groupId' => $id]);
      }
    }
    foreach ($groups as $id) {
      EventService::fireEvent('relation_change', 'object', 'imagegroup', $id);
    }
  }

  function addCustomSearch($query,&$parts) {
    $custom = $query->getCustom();
    if (isset($custom['group'])) {
      $parts['tables'][] = 'imagegroup_image';
      $parts['limits'][] = 'imagegroup_image.image_id=object.id';
      $parts['limits'][] = 'imagegroup_image.imagegroup_id=' . Database::int($custom['group']);
    }
    if (isset($custom['nogroup']) && $custom['nogroup'] === true) {
      $parts['joins'][] = 'left join imagegroup_image on imagegroup_image.image_id=image.object_id';
      $parts['limits'][] = 'object.id = image.object_id';
      $parts['limits'][] = 'imagegroup_image.imagegroup_id is null';
    }
    if (isset($custom['createdAfter'])) {
      $parts['limits'][] = '`object`.`created` > ' . Database::datetime($custom['createdAfter']);
    }
    if (isset($custom['unused']) && $custom['unused'] === true) {
      $ids = ImageService::getUsedImageids();
      if (count($ids) > 0) {
        $parts['limits'][] = 'object.id not in (' . implode(',',$ids) . ')';
      }
    }
  }

//////////////////////////// Persistence //////////////////////////

  function sub_publish() {
    $data =
    '<image xmlns="' . parent::_buildnamespace('1.0') . '">' .
    '<filename>' . Strings::escapeEncodedXML($this->filename) . '</filename>' .
    '<size>' . Strings::escapeEncodedXML($this->size) . '</size>' .
    '<width>' . Strings::escapeEncodedXML($this->width) . '</width>' .
    '<height>' . Strings::escapeEncodedXML($this->height) . '</height>' .
    '<mimetype>' . Strings::escapeEncodedXML($this->mimetype) . '</mimetype>' .
    '</image>';
    return $data;
  }

  function removeMore() {
    @unlink ($basePath . 'images/' . $this->filename);
    $this->clearCache();

    $this->fireRelationChangeEventOnGroups();

    $sql = "delete from imagegroup_image where image_id = @int(id)";
    Database::delete($sql, ['id' => $this->id]);
  }

  function fireRelationChangeEventOnGroups() {
    $sql = "select imagegroup_id from imagegroup_image where image_id = @int(id)";
    $result = Database::select($sql, ['id' => $this->id]);
    while ($row = Database::next($result)) {
      EventService::fireEvent('relation_change','object','imagegroup',$row['imagegroup_id']);
    }
    Database::free($result);
  }

}
?>