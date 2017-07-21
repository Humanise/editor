<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Issues
 */
require_once '../../../Include/Private.php';

$writer = new ItemsWriter();

$writer->startItems();

$writer->item([
  'title' => ['Issues', 'da'=>'Sager'],
  'value' => 'all', 'icon'=>'view/list',
  'badge' => IssueService::getTotalIssueCount()
]);

$writer->item([
  'title' => ['Feedback', 'da'=>'Feedback'],
  'value' => 'babbelab',
  'kind' => 'feedback',
  'icon' => 'view/list'
]);

$writer->endItems();
?>