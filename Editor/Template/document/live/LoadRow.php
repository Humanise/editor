<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');

if ($row = DocumentRow::load($id)) {
  Response::sendObject([
    'id' => $row->getId(),
    'layout' => $row->getLayout(),
    'class' => $row->getClass(),
    'style' => $row->getStyle()
  ]);
} else {
  Response::notFound();
}

?>