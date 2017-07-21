<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../Include/Private.php';

$list = Frame::search();

$writer = new ListWriter();

$writer->startList()->
  startHeaders()->
  header(['title'=>'Navn'])->
  header(['title'=>'Titel'])->
  header(['title'=>'Hierarki'])->
endHeaders();

foreach ($list as $object) {
  $hierarchy = Hierarchy::load($object->getHierarchyId());
  $writer->startRow([ 'kind'=>'frame', 'id'=>$object->getId()])->
    startCell()->text($object->getName())->endCell()->
    startCell()->text($object->getTitle())->endCell()->
    startCell()->text($hierarchy ? $hierarchy->getName() : '!! findes ikke !!')->endCell()->
  endRow();
}
$writer->endList();
?>