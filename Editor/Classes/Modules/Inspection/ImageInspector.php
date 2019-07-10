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
    $sql = "select object.title,object.id, image.type, image.filename, image.size from image, object where object.id=image.object_id";
    $result = Database::select($sql);
    while ($row = Database::next($result)) {
      $entity = ['type' => 'image', 'title' => $row['title'], 'id' => $row['id'], 'icon' => 'common/image','filename' => $row['filename']];
      $path = ConfigurationService::getImagePath($row['filename']);
      if (!in_array($row['type'], ['image/png','image/jpeg','image/gif'])) {
        $inspections[] = $this->makeError($entity, 'The image has an unknown mimetype: ' . $row['type']);
      }
      if (!file_exists($path)) {
        $inspections[] = $this->makeError($entity, 'File was not found (' . $row['filename'] . ')');
      }
      else {
        $size = filesize($path);
        if ($size !== intval($row['size'])) {
          $inspections[] = $this->makeError($entity, 'File size is different (is: ' . $size . ', expected: ' . $row['size'] . ')');
        }
      }
    }
    Database::free($result);
    return $inspections;
  }

  private function makeError($entity, $text) {
    $inspection = new Inspection();
    $inspection->setCategory('model');
    $inspection->setEntity($entity);
    $inspection->setStatus('error');
    $inspection->setText($text);
    return $inspection;
  }
}