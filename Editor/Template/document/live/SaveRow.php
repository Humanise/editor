<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');
$style = Request::getString('style');
$class = Request::getString('class');
$layout = Request::getString('layout');

$sql = "select `id`,`style`,`class`,`page_id` from `document_row` where `id`=@int(id)";

if ($row = Database::selectFirst($sql, ['id' => $id])) {
  $sql="update document_row set `class`=@text(class), `style`=@text(style), `layout`=@text(layout) where id=@int(id)";
  Database::update($sql,['id' => $id, 'style' => $style, 'class' => $class, 'layout' => $layout]);

  PageService::markChanged(intval($row['page_id']));
} else {
  Response::notFound();
}
?>