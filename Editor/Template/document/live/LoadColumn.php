<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');

if ($column = DocumentColumn::load($id)) {
  Response::sendObject([
    'id' => $column->getId(),
    'class' => $column->getClass(),
    'style' => $column->getStyle()
  ]);
} else {
  Response::notFound();
}

?>