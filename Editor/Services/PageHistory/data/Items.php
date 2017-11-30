<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.PageHistory
 */
require_once '../../../Include/Private.php';

$pageId = Request::getInt('pageId');

$writer = new ItemsWriter();
$writer->startItems();

$history = PageService::listHistory($pageId);
foreach ($history as $row) {
  $writer->item([
    'icon' => 'common/time',
    'value' => $row['id'],
    'title' => Dates::formatLongDateTime($row['time'])
  ]);
}
$writer->endItems();
?>