<?php
/**
 * @package OnlinePublisher
 * @subpackage Customers
 */
require_once '../../../Include/Private.php';

$writer = new ListWriter();

$list = Query::after('issue')->withRelationToPage(InternalSession::getPageId())->get();

$writer->startList();
  foreach ($list as $issue) {
    $writer->startRow(['id'=>$issue->getId(), 'kind'=>$issue->getType()])->
      startCell()->
        startLine()->text($issue->getNote())->endLine()->
        startLine(['minor'=>true, 'dimmed'=>true])->text(IssueService::translateKind($issue->getKind()))->endLine()->
      endCell()->
      startCell(['width'=>1]);
      $writer->startIcons()->
        icon(['icon'=>'monochrome/edit', 'revealing'=>true, 'action'=>true, 'data'=>['action'=>'view']])->
      endIcons();
      $writer->endCell()->
    endRow();
  }
$writer->endList();

?>