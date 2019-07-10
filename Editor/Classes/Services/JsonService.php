<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class JsonService {

  static function decode($data) {
    return Strings::fromJSON($data);
  }

  static function readFile($path, $asArray = false) {
    if (file_exists($path)) {
      $json = file_get_contents($path);
      $obj = Strings::fromJSON($json, $asArray);
      $obj = Strings::fromUnicode($obj);
      return $obj;
    }
    return null;
  }
}