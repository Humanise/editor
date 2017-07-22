<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Links
 */
require_once '../../../Include/Private.php';

$source = Request::getString('source');
$target = Request::getString('target');
$state = Request::getString('state');

if ($target == 'all') {
  $target = null;
}
if ($source == 'all') {
  $source = null;
}

$query = new LinkQuery();
$query->withTargetType($target)->withSourceType($source)->withTextCheck();

if ($state == 'warnings') {
  $query->withOnlyWarnings();
}


$links = LinkService::search($query);

$writer = new ListWriter();

$writer->startList();
$writer->startHeaders();
$writer->header(['title' => ['Source', 'da' => 'Kilde']]);
$writer->header(['width' => 1]);
$writer->header();
$writer->header(['title' => ['Target', 'da' => 'Mål']]);
$writer->header(['title' => 'Status']);
$writer->endHeaders();

$icons = [
  'hierarchy' => 'monochrome/hierarchy',
  'file' => 'monochrome/file',
  'url' => 'monochrome/globe',
  'email' => 'monochrome/email',
  'page' => 'common/page',
  'news' => 'common/news',
  'image' => 'common/image'
];

foreach ($links as $link) {
  $sourceIcon = $icons[$link->getSourceType()];
  $targetIcon = $icons[$link->getTargetType()];
  $writer->startRow()->
    startCell(['icon' => $sourceIcon])->text($link->getSourceTitle());
    if ($link->getSourceSubId()) {
      $writer->badge(['text' => '#' . $link->getSourceSubId()]);
    }
    if ($link->getSourceType() == 'news') {
      $writer->startIcons()->
        icon(['icon' => 'monochrome/info', 'action' => true, 'revealing' => true, 'data' => ['action' => 'newsInfo', 'id' => $link->getSourceId()]])->
      endIcons();
    }
    if ($link->getSourceType() == 'page') {
      $writer->startIcons()->
        icon(['icon' => 'monochrome/view', 'action' => true, 'revealing' => true, 'data' => ['action' => 'viewPage', 'id' => $link->getSourceId()]])->
        icon(['icon' => 'monochrome/edit', 'action' => true, 'revealing' => true, 'data' => ['action' => 'editPage', 'id' => $link->getSourceId()]])->
      endIcons();
    }
    $writer->endCell();
    $writer->startCell();
    $writer->endCell()->
    startCell()->startLine(['dimmed' => true])->text($link->getSourceText());
    if ($link->hasError(LinkView::$TEXT_NOT_FOUND)) {
      $writer->startIcons()->icon(['icon' => 'common/warning', 'hint' => ['The text does not exist', 'Teksten findes ikke']])->endIcons();
    }
    $writer->endLine()->endCell()->
    startCell(['icon' => $targetIcon])->startWrap()->text($link->getTargetTitle())->endWrap();
    if ($link->getTargetType() == 'page' && !$link->hasError(LinkView::$TARGET_NOT_FOUND)) {
      $writer->startIcons()->
        icon(['icon' => 'monochrome/view', 'action' => true, 'revealing' => true, 'data' => ['action' => 'viewPage', 'id' => $link->getTargetId()]])->
        icon(['icon' => 'monochrome/edit', 'action' => true, 'revealing' => true, 'data' => ['action' => 'editPage', 'id' => $link->getTargetId()]])->
      endIcons();
    }
    if ($link->getTargetType() == 'file' && !$link->hasError(LinkView::$TARGET_NOT_FOUND)) {
      $writer->startIcons()->
        icon(['icon' => 'monochrome/info', 'action' => true, 'revealing' => true, 'data' => ['action' => 'fileInfo', 'id' => $link->getTargetId()]])->
      endIcons();
    }
    if ($link->getTargetType() == 'url') {
      $writer->startIcons()->
        icon(['icon' => 'monochrome/view', 'action' => true, 'revealing' => true, 'data' => ['action' => 'viewUrl', 'url' => $link->getTargetId()]])->
      endIcons();
    }

    $writer->endCell();
    $writer->startCell();
    foreach ($link->getErrors() as $error) {
      $writer->startLine()->icon(['icon' => 'common/warning'])->text($error['message'])->endLine();
    }
    $writer->endCell();
  $writer->endRow();
}

$writer->endList();
?>