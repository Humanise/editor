<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class ObjectLinkService {

  static function search($query = []) {
    $sql = "select object_link.*,page.title as page_title, object.title as file_title from object_link " .
        "left join page on page.id=object_link.target_value " .
        "left join object on object.id=object_link.target_value and object.type='file'";
    if ($query['objectId']) {
      $sql .= " where object_id = @int(objectId)";
    }
    $sql .= " order by object_link.position";
    $list = [];
    $result = Database::selectAll($sql, ['objectId' => $query['objectId']]);
    foreach ($result as $row) {
      Log::info($row);
      $link = new ObjectLink();
      $link->setId(intval($row['id']));
      $link->setObjectId(intval($row['object_id']));
      $link->setText($row['title']);
      $link->setAlternative($row['alternative']);
      $link->setType($row['target_type']);
      $link->setValue($row['target_value']);
      $link->setPosition(intval($row['position']));
      if ($row['target_type'] == 'page') {
        $link->setInfo($row['page_title']);
      } else if ($row['target_type'] == 'file') {
        $link->setInfo($row['file_title']);
      } else {
        $link->setInfo($row['target_value']);
      }
      $list[] = $link;
    }
    return $list;
  }

  static function getLinks($object) {
    return ObjectLinkService::search(['objectId' => $object->getId()]);
  }

  static function updateLinks($id, $links) {
    $sql = "delete from object_link where object_id = @id";
    Database::delete($sql, $id);
    $position = 1;
    foreach ($links as $link) {
      $obj = new ObjectLink();
      $obj->setObjectId($id);
      $obj->setPosition($position);
      $obj->setText($link->getText());
      $obj->setType($link->getType());
      $obj->setValue($link->getValue());
      $obj->save();
      $position++;
    }
  }

  static function getLinkCounts($objects) {
    if (count($objects) == 0) {
      return [];
    }
    $ids = [];
    foreach ($objects as $object) {
      $ids[] = $object->getId();
    }
    $counts = [];
    $sql = "select object_id as id,count(object_id) as count from object_link where object_id in @ints(ids) group by object_id";
    foreach (Database::selectAll($sql, ['ids' => $ids]) as $row) {
      $counts[$row['id']] = $row['count'];
    }
    return $counts;
  }

  static function addPageLink($object,$page,$linkText) {
    ObjectLinkService::addLink($object,$linkText,'','page',$page->getId());
  }

  static function addLink($object,$title,$alternative,$targetType,$targetValue) {
    $sql = "select max(`position`) as `position` from object_link where object_id = @id";
    if ($row = Database::selectFirst($sql, $object->id)) {
      $pos = $row['position'] + 1;
    } else {
      $pos = 1;
    }

    $link = new ObjectLink();
    $link->setObjectId($object->id);
    $link->setText($title);
    $link->setPosition($pos);
    $link->setAlternative($alternative);
    $link->setType($targetType);
    $link->setValue($targetValue);
    $link->save();

    $sql = "update `object` set updated=now() where id = @id";
    Database::update($sql, ['id' => $object->id]);
  }

}