<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Publish
 */
require_once '../../Include/Private.php';

$pages = PublishingService::getUnpublishedPages();
$hierarchies = PublishingService::getUnpublishedHierarchies();
$objects = PublishingService::getUnpublishedObjects();

$writer = new ListWriter();

$writer->startList();
$writer->startHeaders();
$writer->header(['title'=>['Title','da'=>'Titel'],'width'=>70]);
$writer->header(['title'=>'Type','width'=>30]);
$writer->header();
$writer->endHeaders();

foreach ($pages as $page) {
  $writer->startRow(['kind'=>'page','id'=>$page['id']])->
    startCell(['icon'=>'common/page'])->
      text($page['title'])->
      startIcons()->
        icon(['icon'=>'monochrome/view','revealing'=>true,'action'=>true,'data'=>['action'=>'viewPage','id'=>$page['id']]])->
        icon(['icon'=>'monochrome/edit','revealing'=>true,'action'=>true,'data'=>['action'=>'editPage','id'=>$page['id']]])->
      endIcons()->
    endCell()->
    startCell()->text(['Page','da'=>'Side'])->endCell()->
    startCell(['wrap'=>false])->button(['text'=>['Publish','da'=>'Udgiv']])->endCell()->
  endRow();
}

foreach ($hierarchies as $hierarchy) {
  $writer->startRow(['kind'=>'hierarchy','id'=>$hierarchy['id']]);
  $writer->startCell(['icon'=>'common/hierarchy'])->text($hierarchy['name'])->endCell();
  $writer->startCell()->text(['Hierarchy','da'=>'Hierarki'])->endCell();
  $writer->startCell(['wrap'=>false])->button(['text'=>['Publish','da'=>'Udgiv']])->endCell();
  $writer->endRow();
}

foreach ($objects as $object) {
  $writer->startRow(['kind'=>'object','id'=>$object->getId()]);
  $writer->startCell(['icon'=>$object->getIcon()])->text($object->getTitle())->endCell();
  $writer->startCell()->text($object->getType())->endCell();
  $writer->startCell(['wrap'=>false])->button(['text'=>['Publish','da'=>'Udgiv']])->endCell();
  $writer->endRow();
}

$writer->endList();
?>