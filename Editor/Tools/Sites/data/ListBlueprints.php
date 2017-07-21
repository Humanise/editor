<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../Include/Private.php';

$windowSize = Request::getInt('windowSize',30);
$windowPage = Request::getInt('windowPage',0);

$query = Query::after('pageblueprint')->orderBy('title')->withWindowPage($windowPage)->withWindowSize($windowSize);
$result = $query->search();

$writer = new ListWriter();

$writer->startList();
$writer->sort($sort,$direction);
$writer->window([ 'total' => $result->getTotal(), 'size' => $windowSize, 'page' => $windowPage ]);
$writer->startHeaders();
$writer->header(['title'=>'Titel']);
$writer->header(['title'=>'Ramme']);
$writer->header(['title'=>'Type']);
$writer->header(['title'=>'Design']);
$writer->endHeaders();

foreach ($result->getList() as $object) {
  $frame = Frame::load($object->getFrameId());
  $template = TemplateService::getTemplateById($object->getTemplateId());
  $design = Design::load($object->getDesignId());
  $writer->startRow([ 'kind'=>'blueprint', 'id'=>$object->getId(), 'icon'=>$object->getIcon(), 'title'=>$object->getTitle() ]);
  $writer->startCell()->text($object->getTitle())->endCell();
  $writer->startCell()->text($frame ? $frame->getName() : '?')->endCell();
  $writer->startCell()->text($template ? $template->getName() : '?')->endCell();
  $writer->startCell()->text($design ? $design->getTitle() : '?')->endCell();
  $writer->endRow();
}
$writer->endList();
?>