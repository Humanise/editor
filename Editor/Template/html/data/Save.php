<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Html
 */
require_once '../../../Include/Private.php';

$id = Request::getId();
$title = Request::getString('title');
$html = Request::getString('html');

if ($obj = HtmlTemplate::load($id)) {
  $obj->setTitle($title);
  $obj->setHtml($html);
  $obj->save();
  PageService::markChanged($id);
}
?>