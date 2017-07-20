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
    $path = Request::getString('path');
    $method = '';
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
    } else {
      echo $method;
    }
  }

  static function appPost() {
    if (!AuthenticationService::isInternalUser(Request::getString('username'),Request::getString('password'))) {
      Response::badRequest();
      return;
    }
    $text = Request::getString('text');
    if (isset($_FILES['file'])) {
      $fileName = $_FILES['file']['name'];
      $tempFile = $_FILES['file']['tmp_name'];
      $title = 'Photo from app';
      $result = ImageService::createImageFromFile($tempFile,$fileName,$title);
      if ($result->getSuccess()) {
        if (Strings::isNotBlank($text)) {
          $image = $result->getObject();
          $image->setNote($text);
          $image->save();
          $image->publish();
        }
      }
    }
    else if (Strings::isNotBlank($text)) {
      $news = new News();
      $news->setTitle(Strings::shortenString($text, 20));
      $news->setNote($text);
      $news->save();
      $news->publish();
    }
  }

  static function images() {
    if (!AuthenticationService::isInternalUser(Request::getString('username'),Request::getString('password'))) {
      Response::badRequest();
      return;
    }
    $query = Query::after('image')->withWindowSize(100)->withDirection('ascending')->orderBy('title');
    $result = $query->search();

    $objects = $result->getList();

    Response::sendObject($objects);
  }

  static function prototype() {
    $name = Request::getString('name');
    $parts = explode('/', $name);
    if (count($parts) == 2) {
      $design = $parts[0];
      $prototype = FileSystemService::getFullPath('style/' . $design . '/prototype/' . $parts[1] . '.xml');
      if (is_file($prototype)) {
        $page = [
          'xml' => file_get_contents($prototype),
          'design' => $design,
          'template' => 'document',
          'language' => 'en',
          'published' => 0,
          'secure' => false,
          'dynamic' => false,
          'framedynamic' => false,
          'id' => 0
        ];
        $id = 0;
        $path = '/';
        $relative = '/';
        $samePageBaseUrl = '/';
        RenderingService::writePage($id,$path,$page,$relative,$samePageBaseUrl);
      } else {
        Response::notFound($prototype . ' does not exist');
      }
    }
  }
}