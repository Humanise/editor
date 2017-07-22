<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Waterusage
 */
require_once '../../../Include/Private.php';

$years = WaterusageService::getYearCounts();
$states = WaterusageService::getStatusCounts();

$writer = new ItemsWriter();

$writer->startItems();

$writer->title('Status');
foreach ($states as $info) {
  $writer->item([
    'value' => $info['status'],
    'title' => WaterusageService::getStatusText($info['status']),
    'badge' => $info['count'],
    'icon' => WaterusageService::getStatusIcon($info['status']),
    'kind' => 'status'
  ]);
}

$writer->title('Aflæsninger');
$writer->item([
  'value' => 'usage',
  'title' => 'Alle aflæsninger',
  'icon' => 'common/water'
]);

foreach ($years as $info) {
  if (!$info['year']) {
    continue;
  }
  $writer->item([
    'value' => $info['year'],
    'title' => $info['year'],
    'badge' => $info['count'],
    'icon' => 'common/time',
    'kind' => 'year'
  ]);
}
$writer->endItems();
?>