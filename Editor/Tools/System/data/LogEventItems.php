<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Include/Private.php';

$list = LogService::getUsedEvents();

$writer = new ItemsWriter();

$writer->startItems();
$writer->item(['value'=>'all', 'title'=>['All events', 'da'=>'Alle begivenheder']]);
foreach($list as $item) {
  $writer->startItem(['value'=>$item, 'title'=>$item])->endItem();
}
$writer->endItems();
?>