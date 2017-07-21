<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Include/Private.php';

$writer = new ListWriter();

$writer->startList();
$writer->startHeaders();
$writer->header(['title'=>['Name', 'da'=>'Navn'], 'width'=>40]);
$writer->header(['title'=>['Username', 'da'=>'Brugernavn']]);
$writer->header(['title'=>['E-mail', 'da'=>'E-post']]);
$writer->header(['title'=>['Language', 'da'=>'Sprog']]);
$writer->header(['title'=>['Internal', 'da'=>'Intern'], 'align'=>'center']);
$writer->header(['title'=>['External', 'da'=>'Ekstern'], 'align'=>'center']);
$writer->header(['title'=>'Administrator', 'align'=>'center']);
$writer->endHeaders();

$list = Query::after('user')->get();
foreach ($list as $item) {
  $writer->startRow(['kind'=>'user', 'id'=>$item->getId()]);
  $writer->startCell(['icon'=>$item->getIcon()])->text($item->getTitle())->endCell();
  $writer->startCell()->text($item->getUsername())->endCell();
  $writer->startCell()->text($item->getEmail())->endCell();
  $writer->startCell()->text($item->getLanguage())->endCell();
  $writer->startCell(['align'=>'center']);
  if ($item->getInternal()) {
    $writer->startIcons()->icon('monochrome/checkmark')->endIcons();
  }
  $writer->endCell();
  $writer->startCell(['align'=>'center']);
  if ($item->getExternal()) {
    $writer->startIcons()->icon('monochrome/checkmark')->endIcons();
  }
  $writer->endCell();
  $writer->startCell(['align'=>'center']);
  if ($item->getAdministrator()) {
    $writer->startIcons()->icon('monochrome/checkmark')->endIcons();
  }
  $writer->endCell();
  $writer->endRow();
}
$writer->endList();
?>