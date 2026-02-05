<?php
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class Response {

  public static $OK = 200;
  public static $FORBIDDEN = 403;
  public static $NOT_FOUND = 404;
  public static $UNAUTHORIZED = 401;
  public static $UNAVAILABLE = 503;

  public static function sendObject($obj): void {
    if (!ConfigurationService::isUnicode()) {
      $obj = Strings::toUnicode($obj);
    }
    header('Content-Type: text/plain; charset=utf-8');
    $str = Strings::toJSON($obj);
    /* TODO May be overkill since JSON is often very small
    $supportsGzip = strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false;
    if ($supportsGzip) {
        $str = gzencode($str,9);
        header('Content-Encoding: gzip');
    }*/
    echo $str;
  }

  public static function setExpiresInDays($days = 0): void {
    Response::setExpiresInHours($days * 24);
  }

  public static function setExpiresInHours($hours = 0): void {
    $offset = 60 * 60 * $hours;

    $modified = ConfigurationService::getDeploymentTime();
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $modified) . ' GMT', true, 200);
    header("Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT");
    header("Cache-Control: max-age=$offset, must-revalidate");
    header("Pragma: hack");
  }

  public static function noCache(): void {
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header("Expires: " . gmdate("D, d M Y H:i:s", 0) . " GMT");
  }

  public static function redirect($url): void {
    session_write_close();
    header('Location: ' . $url);
    exit();
  }

  public static function redirectMoved($url): void {
    session_write_close();
    header('HTTP/1.1 301 Moved Permanently', replace: true, response_code: 301);
    header('Location: ' . $url);
    exit();
  }

  public static function contentDisposition($filename): void {
    header("Content-Disposition: attachment; filename=\"$filename\"");
  }

  public static function internalServerError($text = null): void {
    Response::sendStatus(500,$text);
  }

  public static function badGateway($text = null): void {
    Response::sendStatus(502,$text);
  }

  public static function badRequest($text = null): void {
    Response::sendStatus(400,$text);
  }

  public static function notFound($text = null): void {
    Response::sendStatus(404,$text);
  }

  public static function unauthorized($text = null): void {
    Response::sendStatus(Response::$UNAUTHORIZED,$text);
  }

  public static function forbidden($text = null): void {
    Response::sendStatus(Response::$FORBIDDEN,$text);
  }

  public static function uploadSuccess(): void {
    Response::text('SUCCESS');
  }

  public static function uploadFailure(): void {
    Response::badRequest();
    Response::text('FAILURE');
  }

  public static function text(string $text): void {
    header("Content-Type: text/plain; charset=UTF-8");
    echo $text;
  }

  public static function html(string $html): void {
    header("Content-Type: text/html; charset=UTF-8");
    echo $html;
  }

  public static function sendStatus($number, $text = null): void {
    http_response_code($number);
    if ($text) {
      echo '<!DOCTYPE html><html><head><title>' . $text . '</title></head><body><h1>' . $text . '</h1><p>' . $number . '</p></body></html>';
    }
  }
}
?>