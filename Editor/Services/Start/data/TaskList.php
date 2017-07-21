<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../Include/Private.php';

$list = Query::after('issue')->withoutProperty('kind',Issue::$error)->withProperty('statusId',0)->get();

//$list = array();

$writer = new ListWriter();

$writer->startList();

foreach($list as $item) {
  $page = PageQuery::getRows()->withRelationFrom($item)->first();
  $writer->startRow()->
    startCell(['variant'=>'card']);
    $writer->startLine(['mini'=>false])->text(Strings::shortenString($item->getNote(),300))->endLine();
    $writer->startLine(['dimmed'=>true,'mini'=>true])->text(Dates::formatDateTime($item->getCreated()))->text(' - ')->text(IssueService::translateKind($item->getKind()))->endLine();
    if ($page) {
      $writer->startLine(['class'=>'task_page'])->
        object(['icon'=>'common/page','text'=>$page['title']])->
        startIcons()->
          icon(['icon'=>'monochrome/view','action'=>true,'revealing'=>true,'data'=>['id'=>$page['id'],'action'=>'view']])->
          icon(['icon'=>'monochrome/edit','action'=>true,'revealing'=>true,'data'=>['id'=>$page['id'],'action'=>'edit']])->
        endIcons()->
      endLine();
    }
    $writer->endCell()->
  endRow();
}
$writer->endList();
?>