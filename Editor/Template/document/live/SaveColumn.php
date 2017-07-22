<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');
$style = Request::getString('style');
$class = Request::getString('class');

$sql = "select `id`,`page_id` from `document_column` where `id`=@int(id)";

if ($row = Database::selectFirst($sql, ['id' => $id])) {
  $sql = "update document_column set `class`=@text(class), `style`=@text(style) where id=@int(id)";
  Database::update($sql,['id' => $id, 'style' => $style, 'class' => $class]);

  PageService::markChanged(intval($row['page_id']));
} else {
  Response::notFound();
}
?>