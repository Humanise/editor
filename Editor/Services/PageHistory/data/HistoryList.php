<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.PageHistory
 */
require_once '../../../Include/Private.php';

$pageId = Request::getInt('pageId');

$writer = new ListWriter();

$writer->startList()->
  startHeaders()->
    header(['title' => ['User', 'da' => 'Bruger'], 'width' => 20])->
    header(['title' => ['Time', 'da' => 'Tidspunkt'], 'width' => 20])->
    header(['title' => ['Description', 'da' => 'Beskrivelse']])->
    header(['width' => 1])->
  endHeaders();

$history = PageService::listHistory($pageId);

foreach ($history as $row) {
  $writer->startRow(['id' => $row['id']])->
    startCell(['icon' => 'common/user'])->text($row['title'])->endCell()->
    startCell(['wrap' => false])->text(Dates::formatLongDateTime($row['time']))->endCell()->
    startCell()->text($row['message'])->startIcons()->icon(['icon' => 'monochrome/edit', 'revealing' => true, 'action' => true, 'data' => ['action' => 'editMessage']])->endIcons()->endCell()->
    startCell()->button(['text' => ['View', 'da' => 'Vis']])->endCell()->
    endRow();
}
$writer->endList();
?>