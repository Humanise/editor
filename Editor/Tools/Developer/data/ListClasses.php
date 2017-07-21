<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Developer
 */
require_once '../../../Include/Private.php';

$writer = new ListWriter();

$writer->startList()->
  startHeaders()->
    header(['title'=>'Name','width'=>40])->
    header(['title'=>'Parent'])->
    header(['title'=>'Properties'])->
  endHeaders();

$list = ClassService::getClasses();
foreach ($list as $item) {
  $writer->startRow(['kind'=>'class','id'=>$item['name']])->
    startCell(['icon'=>'common/object'])->
      startLine()->text($item['name'])->endLine()->
      startLine(['dimmed'=>true,'minor'=>true])->text($item['relativePath'])->endLine()->
    endCell()->
    startCell()->text($item['parent'])->endCell()->
    startCell()->text(Strings::toJSON($item['properties']))->endCell()->
  endRow();
}
$writer->endList();
?>