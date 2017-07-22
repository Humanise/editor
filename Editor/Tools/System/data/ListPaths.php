<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Include/Private.php';

$writer = new ListWriter();

$writer->startList();
$writer->startHeaders();
$writer->header(['title' => ['Path', 'da' => 'Sti'], 'width' => 40]);
$writer->header(['title' => ['Page', 'da' => 'Side'], 'width' => 30]);
$writer->header(['title' => ['Page path', 'da' => 'Side-sti'], 'width' => 30]);
$writer->endHeaders();

$list = Query::after('path')->get();
foreach ($list as $item) {
  $page = Page::load($item->getPageId());
  $writer->startRow(['kind' => 'path', 'id' => $item->getId()]);
  $writer->startCell(['icon' => $item->getIcon()])->text($item->getPath())->endCell();
  if ($page) {
    $writer->startCell(['icon' => $page->getIcon()])->text($page->getTitle())->endCell();
  } else {
    $writer->startCell(['icon' => 'common/warning'])->text(['No page', 'da' => 'Ingen siden'])->endCell();
  }
  $writer->startCell()->text($page ? $page->getPath() : '')->endCell();
  $writer->endRow();
}
$writer->endList();
?>