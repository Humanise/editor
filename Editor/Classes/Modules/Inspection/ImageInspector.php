<?php
/**
 * @package OnlinePublisher
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}
class ImageInspector implements Inspector {

  function inspect() {
    $inspections = [];
    $sql = "select object.title,object.id, image.type, image.filename from image, object where object.id=image.object_id and image.type not in ('image/png','image/jpeg','image/gif')";
    $result = Database::select($sql);
    while ($row = Database::next($result)) {
      $entity = ['type' => 'image', 'title' => $row['title'], 'id' => $row['id'], 'icon' => 'common/image'];
      $inspection = new Inspection();
      $inspection->setCategory('model');
      $inspection->setEntity($entity);
      $inspection->setStatus('error');
      $inspection->setText('The image (' . $row['filename'] . ') has an unknown mimetype: ' . $row['type']);
      $inspections[] = $inspection;
    }
    Database::free($result);
    return $inspections;
  }

}