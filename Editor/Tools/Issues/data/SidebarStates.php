<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Issues
 */
require_once '../../../Include/Private.php';

$states = IssueService::getStatusCounts();

$writer = new ItemsWriter();

$writer->startItems();

if ($states) {
  $writer->title(['States', 'da' => 'Status']);
  $writer->item(['title' => ['Any state', 'da' => 'Enhver status'], 'value' => 'any', 'icon' => 'view/list']);
  foreach ($states as $row) {
    $writer->item([
      'title' => $row['title'] ? $row['title'] : ['No status', 'da' => 'Ingen status'],
      'icon' => 'view/list',
      'badge' => $row['count'],
      'kind' => 'status',
      'value' => $row['id'] ? $row['id'] : 'nostatus'
    ]);
  }
}
$writer->endItems();
?>