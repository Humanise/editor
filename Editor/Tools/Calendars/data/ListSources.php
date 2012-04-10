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
		->header(array('title'=>'Titel','width'=>30))
		->header(array('title'=>'Adresse'))
		->header(array('title'=>'Filter'))
		->header(array('title'=>'Interval'))
		->header(array('title'=>'Synkroniseret'))
	->endHeaders();

foreach ($sources as $source) {
	$writer
		->startRow(array('kind'=>'calendarsource','id'=>$source->getId()))
		->startCell(array('icon'=>$source->getIn2iGuiIcon()))
			->startLine()->text($source->getTitle())->endLine()
			->startLine(array('dimmed'=>true))->text($source->getDisplayTitle())->endLine()
		->endCell()
		->startCell()->startLine(array('dimmed'=>true,'mini'=>true))->startWrap()->text($source->getUrl())->endWrap()->endLine()->endCell()
		->startCell()->startLine(array('mini'=>true))->text($source->getFilter())->endLine()->endCell()
		->startCell(array('wrap'=>false))->text(DateUtils::formatDuration($source->getSyncInterval()))->endCell()
		->startCell(array('wrap'=>false))->text(DateUtils::formatFuzzy($source->getSynchronized()))->endCell()
	->endRow();
}
$writer->endList();