<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Guestbook
 */
require_once '../../../Include/Private.php';

$writer = new ListWriter();

$writer->startList()->
  startHeaders()->
    header(['title' => 'Tid / Navn', 'width' => 30])->
    header(['title' => 'Besked'])->
    header(['width' => 1])->
  endHeaders();
$sql = "select UNIX_TIMESTAMP(time) as time,name,text,id from guestbook_item where page_id=" . Database::int(Request::getId()) . " order by time desc";

$result = Database::select($sql);
while ($row = Database::next($result)) {
  $writer->startRow(['id' => $row['id']])->
    startCell(['wrap' => true])->
      startLine()->text($row['name'])->endLine()->
      startLine(['minor' => true, 'dimmed' => true, 'top' => 5])->text(Dates::formatLongDateTime($row['time']))->endLine()->
    endCell()->
    startCell()->text($row['text'])->endCell()->
    startCell()->startIcons()->icon(['icon' => 'monochrome/delete', 'action' => true, 'revealing' => true, 'data' => ['action' => 'delete']])->endIcons()->endCell()->
  endRow();
}
Database::free($result);
$writer->endList();
?>