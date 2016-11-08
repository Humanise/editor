<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');

$sql = "select `id`,`style`,`class`,`layout` from `document_row` where `id`=@int(id)";
$row = Database::selectFirst($sql, ['id' => $id]);
if ($row) {
  Response::sendObject([
    'id' => intval($row['id']),
    'layout' => $row['layout'],
    'class' => $row['class'],
    'style' => $row['style']
  ]);
} else {
  Response::notFound();
}

?>