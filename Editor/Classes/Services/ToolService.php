<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class ToolService {

  static function getInstalled() {
    $arr = [];
    $sql = "select `unique` from `tool`";
    $result = Database::select($sql);
    while ($row = Database::next($result)) {
      $arr[] = $row['unique'];
    }
    Database::free($result);
    return $arr;
  }

  static function getAvailable() {
    $arr = FileSystemService::listDirs(FileSystemService::getFullPath("Editor/Tools/"));
    for ($i = 0; $i < count($arr); $i++) {
      if (substr($arr[$i],0,3) == 'CVS') {
        unset($arr[$i]);
      }
    }
    return $arr;
  }

  static function getCategorized() {
    $categorized = [];
    $installed = ToolService::getInstalled();
    foreach ($installed as $key) {
      $info = ToolService::getInfo($key);
      if ($info) {
        if (!isset($categorized[$info->category])) {
          $categorized[$info->category] = [];
        }
        $categorized[$info->category][$key] = $info;
      } else {
        Log::warn('Tool not found: ' . $key);
      }
    }
    foreach ($categorized as $key => &$value) {
      usort($value,['ToolService', '_priorityComparator']);
    }
    return $categorized;
  }

  static function _priorityComparator($toolA, $toolB) {
    $a = $toolA->priority;
    $b = $toolB->priority;
    if ($a == $b) {
      return 0;
    }
    return ($a < $b) ? -1 : 1;
  }

  static function getInfo($key) {
    $path = FileSystemService::getFullPath("Editor/Tools/" . $key . "/info.json");
    return Strings::toUnicode(JsonService::readFile($path));
  }

  static function install($key) {
    $sql = "select id from `tool` where `unique` = @text(key)";
    if (Database::isEmpty($sql, ['key' => $key])) {
      $sql = "insert into tool (`unique`) values (@text(key))";
      Database::insert($sql, ['key' => $key]);
    }
  }

  static function uninstall($key) {
    $sql = "delete from `tool` where `unique` = @text(key)";
    Database::delete($sql, ['key' => $key]);
  }
}