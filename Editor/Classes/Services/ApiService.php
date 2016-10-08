<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class ApiService {

	static function handle() {
    if (!AuthenticationService::isInternalUser(Request::getString('username'),Request::getString('password'))) {
      Response::badRequest();
      return;
    }
    $path = Request::getString('path');
    if (preg_match('/api((\/[a-z]+)+)/', $path, $matches)) {
      $sub = substr($matches[1], 1);
      $parts = explode('/',$sub);
      $method = $parts[0];
      for ($i=1; $i < count($parts); $i++) {
        $method.=ucfirst($parts[$i]);
      }
    }
    if (method_exists('ApiService', $method)) {
      ApiService::$method();
    }
	}

  static function appPost() {
    if (isset($_FILES['file'])) {
      $fileName = $_FILES['file']['name'];
      $tempFile = $_FILES['file']['tmp_name'];
      $title = 'Photo from app';
      $result = ImageService::createImageFromFile($tempFile,$fileName,$title);
      if ($result->getSuccess()) {
        $image = $result->getObject();
        $image->setNote(Request::getString('text'));
        $image->save();
        $image->publish();
      }
    }
  }
}