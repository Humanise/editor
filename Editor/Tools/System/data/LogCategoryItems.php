<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Include/Private.php';

$categories = LogService::getUsedCategories();

$writer = new ItemsWriter();

$writer->startItems();
$writer->item(['value'=>'all','title'=>['All categories','da'=>'Alle kategorier']]);
foreach($categories as $category) {
  $writer->startItem(['value'=>$category,'title'=>$category])->endItem();
}
$writer->endItems();
?>