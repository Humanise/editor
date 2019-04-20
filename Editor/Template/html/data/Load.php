<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.HTML
 */
require_once '../../../Include/Private.php';

$id = Request::getId();

$obj = HtmlTemplate::load($id);
if ($obj) {
  Response::sendObject($obj);
} else {
  Response::notFound();
}
?>