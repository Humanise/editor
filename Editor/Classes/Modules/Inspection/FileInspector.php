<?php
/**
 * @package OnlinePublisher
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}
class FileInspector implements Inspector {

  function inspect() {
    $inspections = [];
    $sql = "select object.title,object.id, file.type, file.filename, file.size from file, object where object.id=file.object_id";
    $result = Database::select($sql);
    while ($row = Database::next($result)) {
      $entity = ['type' => 'file', 'title' => $row['title'], 'id' => $row['id'], 'icon' => 'common/file','filename' => $row['filename']];
      $path = ConfigurationService::getFilePath($row['filename']);
      if (!$row['type']) {
        $inspections[] = $this->makeError($entity, 'The file has no mimetype');
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