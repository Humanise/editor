<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');

if ($column = DocumentColumn::load($id)) {
  Response::sendObject($column);
} else {
  Response::notFound();
}
?>