<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../Include/Private.php';

$inspections = InspectionService::performInspection(['status' => Request::getString('status')]);

$writer = new ListWriter();

$writer->startList(['unicode' => true]);
$icons = ['warning' => 'common/warning', 'ok' => 'common/success', 'error' => 'common/stop'];

foreach ($inspections as $inspection) {
  $entity = $inspection->getEntity();
  $writer->startRow();
  $writer->startCell(['icon' => $icons[$inspection->getStatus()]])->text($inspection->getText())->endCell();
  if ($entity) {
    $writer->startCell(['icon' => $entity['icon']])->text($entity['title'])->endCell();
  } else {
    $writer->startCell()->endCell();
  }
  $writer->endRow();
}
$writer->endList();
?>