<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Include/Private.php';

$page = Request::getInt('windowPage');
$showIpSession = Request::getBoolean('showIpSession');
$size = 40;

$result = LogService::getEntries([
  'page' => $page,
  'size' => $size,
  'category' => Request::getString('category'),
  'event' => Request::getString('event'),
  'text' => Request::getString('text')
]);

$writer = new ListWriter();

$writer->startList()->
  sort('time','descending')->
  window([ 'total' => $result->getTotal(), 'size' => $size, 'page' => $page ])->
  startHeaders()->
    header(['title'=>['Time', 'da'=>'Tidspunkt'], 'key'=>'time'])->
    header(['title'=>['Category', 'da'=>'Kategori']])->
    header(['title'=>['Event', 'da'=>'Begivenhed']])->
    header(['title'=>['Entity', 'da'=>'Entitet']])->
    header(['title'=>['Message', 'da'=>'Besked']])->
    header(['title'=>['User', 'da'=>'Bruger']]);
  if ($showIpSession) {
    $writer->header(['title'=>'IP']);
    $writer->header(['title'=>'Session']);
  }
$writer->endHeaders();

foreach ($result->getList() as $row) {
  $writer->startRow(['kind'=>'logEntry']);
  $writer->startCell()->text(Dates::formatLongDateTime($row['time']))->endCell();
  $writer->startCell()->text($row['category'])->endCell();
  $writer->startCell()->text($row['event'])->endCell();
  $writer->startCell()->text($row['entity'])->endCell();
  $writer->startCell()->startWrap()->text($row['message'])->endWrap()->endCell();
  $writer->startCell()->text($row['username'])->endCell();
  if ($showIpSession) {
    $writer->startCell()->text($row['ip'])->endCell();
    $writer->startCell()->text($row['session'])->endCell();
  }
  $writer->endRow();
}
$writer->endList();
?>