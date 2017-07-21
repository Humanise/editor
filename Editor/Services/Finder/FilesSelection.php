<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Finder
 */
require_once '../../Include/Private.php';

$writer = new ItemsWriter();

$writer->startItems();

$writer->item([
  'value' => 'all',
  'title' => ['All','da'=>'Alle'],
  'icon' => 'common/files',
  'kind' => 'all'
]);

$writer->title(['Groups','da'=>'Grupper']);

$groups = FileService::getGroupCounts();

foreach ($groups as $group) {
  $options = [
    'value' => $group['id'],
    'title' => $group['title'],
    'icon' => 'common/folder',
    'kind' => 'filegroup'
  ];
  if ($group['count']>0) {
    $options['badge']=$group['count'];
  }
  $writer->startItem($options)->endItem();
}

$writer->endItems();
?>


