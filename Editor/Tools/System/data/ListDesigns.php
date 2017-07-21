<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Include/Private.php';

$writer = new ListWriter();

$writer->startList()->
  startHeaders()->
    header(['title'=>'Design', 'width'=>40])->
    header(['title'=>['Key', 'da'=>'Nøgle'], 'width'=>30])->
  endHeaders();

$designs = Query::after('design')->get();
foreach ($designs as $item) {
  $writer->
  startRow(['kind'=>'design', 'id'=>$item->getId()])->
    startCell(['icon'=>$item->getIcon()])->text($item->getTitle())->endCell()->
    startCell()->text($item->getUnique())->endCell()->
  endRow();
}
$writer->endList();
?>