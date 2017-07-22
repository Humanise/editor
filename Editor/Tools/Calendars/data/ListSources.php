<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Calendars
 */
require_once '../../../Include/Private.php';

$sources = Query::after('calendarsource')->orderBy('title')->get();

$writer = new ListWriter();

$writer->startList()
  ->startHeaders()
    ->header(['title' => ['Title', 'da' => 'Titel'], 'width' => 30])
    ->header(['title' => ['Address', 'da' => 'Adresse']])
    ->header(['title' => 'Filter'])
    ->header(['title' => 'Interval'])
    ->header(['title' => ['Synchronized', 'da' => 'Synkroniseret']])
  ->endHeaders();

foreach ($sources as $source) {
  $writer
    ->startRow(['kind' => 'calendarsource', 'id' => $source->getId()])
    ->startCell(['icon' => $source->getIcon()])
      ->startLine()->text($source->getTitle())->endLine()
      ->startLine(['dimmed' => true])->text($source->getDisplayTitle())->endLine()
    ->endCell()
    ->startCell()->startLine(['dimmed' => true, 'mini' => true])->startWrap()->text($source->getUrl())->endWrap()->endLine()->endCell()
    ->startCell()->startLine(['mini' => true])->text($source->getFilter())->endLine()->endCell()
    ->startCell(['wrap' => false])->text(Dates::formatDuration($source->getSyncInterval()))->endCell()
    ->startCell(['wrap' => false])->text(Dates::formatFuzzy($source->getSynchronized()))->endCell()
  ->endRow();
}
$writer->endList();