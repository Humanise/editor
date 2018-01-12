<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');

if ($row = DocumentRow::load($id)) {
  Response::sendObject($row);
} else {
  Response::notFound();
}
?>