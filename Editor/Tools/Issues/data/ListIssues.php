<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Issues
 */
require_once '../../../Include/Private.php';

$text = Request::getString('text');
$type = Request::getString('type');
$kind = Request::getString('kind');
$status = Request::getString('status');

if ($type == 'feedback') {
  listFeedback();
} else {
  listIssues();
}

function listFeedback() {
  global $text;

  $query = Query::after('feedback')->withText($text)->orderByCreated()->descending();

  $list = $query->get();

  $writer = new ListWriter();

  $writer->startList(['checkboxes' => true]);

  $writer->startHeaders()->
    header('Name')->
    header('Email')->
    header('Message')->
    header(['title' => 'Oprettet', 'width' => 1])->
  endHeaders();

  foreach($list as $item) {
    $writer->startRow(['id' => $item->getId(), 'kind' => 'feedback'])->
      startCell()->text($item->getName())->endCell()->
      startCell()->text($item->getEmail())->endCell()->
      startCell()->text($item->getMessage())->endCell()->
      startCell(['wrap' => false, 'dimmed' => true])->text(Dates::formatFuzzy($item->getCreated()))->endCell()->
    endRow();
  }
  $writer->endList();
}

function listIssues() {
  global $text,$kind,$status;

  $query = Query::after('issue')->withText($text)->orderByCreated()->descending();
  if ($kind != 'any') {
    $query->withProperty('kind',$kind);
  }
  if ($status != 'any') {
    $query->withProperty('statusId',$status);
  }

  $states = IssueService::getStatusMap();

  $list = $query->get();

  $writer = new ListWriter();

  $writer->startList(['checkboxes' => true]);

  $writer->startHeaders()->
    header()->
    header(['title' => 'Status', 'width' => 1])->
    header(['title' => 'Oprettet', 'width' => 1])->
  endHeaders();

  foreach($list as $item) {
    $pages = PageQuery::rows()->withRelationFrom($item)->search()->getList();
    $page = null;
    if ($pages) {
      $page = $pages[0];
    }
    $writer->startRow(['id' => $item->getId(), 'kind' => 'issue'])->
      startCell()->
      startLine()->startStrong()->text($item->getTitle())->endStrong()->endLine()->
      startLine(['top' => 3])->text($item->getNote())->endLine();
      if ($page) {
        $writer->startLine(['top' => 10])->object(['icon' => 'common/page', 'text' => $page['title']]);
        $writer->startIcons()->
          icon(['icon' => 'monochrome/view', 'action' => true, 'revealing' => true, 'data' => ['id' => $page['id'], 'action' => 'view']])->
          icon(['icon' => 'monochrome/edit', 'action' => true, 'revealing' => true, 'data' => ['id' => $page['id'], 'action' => 'edit']])->
        endIcons();
        $writer->endLine();
      }
      $writer->startLine(['dimmed' => true, 'mini' => true, 'top' => 3])->text(IssueService::translateKind($item->getKind()))->endLine()->
      endCell()->
      startCell()->text(@$states[$item->getStatusId()])->endCell()->
      startCell(['wrap' => false, 'dimmed' => true])->text(Dates::formatFuzzy($item->getCreated()))->endCell()->
    endRow();
  }
  $writer->endList();
}
?>