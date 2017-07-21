<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$pageId = InternalSession::getPageId();

$query = new LinkQuery();
$query->withSourcePage($pageId)->withTextCheck();

$links = LinkService::search($query);

$writer = new ListWriter();

$writer->startList()->
  startHeaders()->
    header(['title'=>['Source', 'da'=>'Kilde'], 'width'=>30])->
    header(['title'=>'Destination'])->
    header()->
    header(['width'=>1])->
  endHeaders();

foreach ($links as $link) {
  $writer->startRow(['id'=>$link->getId()])->
    startCell()->
      startLine()->text($link->getSourceText());
      $writer->startIcons();
    if ($link->hasError(LinkView::$TEXT_NOT_FOUND)) {
      $writer->icon(['icon'=>'monochrome/warning', 'size'=>12, 'hint'=>['The text was not found', 'da'=>'Teksten findes ikke']]);
    }
    if ($link->getType()=='link') {
      $writer->icon(['icon'=>'common/edit', 'revealing'=>true, 'action'=>true, 'data'=>['action'=>'editLink']]);
    }
    $writer->endIcons();


    $writer->endLine();

    if (Strings::isNotBlank($link->getSourceDescription())) {
      $writer->startLine(['dimmed'=>true])->text($link->getSourceDescription())->endLine();
    }
    if ($link->getSourceSubId()) {
      $writer->startLine(['dimmed'=>true, 'minor'=>true])->text(['Only shown in the section: '.$link->getSourceSubId(), 'da'=>'Vises kun i afsnittet: '.$link->getSourceSubId()])->endLine();
    }
    $writer->endCell()->
    startCell(['icon'=>LinkService::getTargetIcon($link)])->
      startLine()->startWrap()->text($link->getTargetTitle())->endWrap();
    if ($link->getTargetType()=='page' && !$link->hasError(LinkView::$TARGET_NOT_FOUND)) {
      $writer->startIcons();
      $writer->icon(['icon' => 'monochrome/info', 'action'=>'true', 'data' => ['action' => 'pageInfo', 'id' => $link->getTargetId()], 'revealing' => true]);
      $writer->icon(['icon' => 'monochrome/edit', 'action'=>'true', 'data' => ['action' => 'editPage', 'id' => $link->getTargetId()], 'revealing' => true]);
      $writer->icon(['icon' => 'monochrome/view', 'action'=>'true', 'data' => ['action' => 'viewPage', 'id' => $link->getTargetId()], 'revealing' => true]);
      $writer->endIcons();
    }
    else if ($link->getTargetType()=='file' && !$link->hasError(LinkView::$TARGET_NOT_FOUND)) {
      $writer->startIcons();
      $writer->icon(['icon' => 'monochrome/info', 'action'=>'true', 'data' => ['action' => 'fileInfo', 'id' => $link->getTargetId()], 'revealing' => true]);
      $writer->icon(['icon' => 'monochrome/view', 'action'=>'true', 'data' => ['action' => 'viewFile', 'id' => $link->getTargetId()], 'revealing' => true]);
      $writer->endIcons();
    }
    else if ($link->getTargetType()=='url') {
      $writer->startIcons();
      $writer->icon(['icon' => 'monochrome/arrow_right_light', 'action'=>'true', 'data' => ['action' => 'visitUrl', 'url' => $link->getTargetId()], 'revealing' => true]);
      $writer->endIcons();
    }
    $writer->endLine()->
      startLine(['dimmed'=>true, 'minor'=>true])->text(LinkService::translateLinkType($link->getTargetType()))->endLine()->
    endCell();
    $writer->startCell();
    foreach ($link->getErrors() as $error) {
      $writer->startLine()->icon(['icon'=>'common/warning'])->text($error['message'])->endLine();
    }
    $writer->endCell();
    $writer->startCell()->
      startIcons()->
      icon(['icon' => 'monochrome/delete', 'action'=>'true', 'data' => ['action' => 'deleteLink'], 'revealing' => true])->
      endIcons()->
    endCell()->
  endRow();
}

$writer->endList();
?>