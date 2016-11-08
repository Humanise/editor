<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');

$sql = "select `id`,`style`,`class` from `document_column` where `id`=@int(id)";
$row = Database::selectFirst($sql, ['id' => $id]);
if ($row) {
  Response::sendObject([
    'id' => intval($row['id']),
    'class' => $row['class'],
    'style' => $row['style']
  ]);
} else {
  Response::notFound();
}

?>