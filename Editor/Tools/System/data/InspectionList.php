<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Include/Private.php';

$writer = new ListWriter();

$writer->startList();
$writer->startHeaders();
$writer->header(['title'=>'Problem','width'=>40]);
$writer->header(['title'=>'Objekt']);
$writer->header(['title'=>'Kategori']);
$writer->header(['width'=>1]);
$writer->endHeaders();

$inspections = InspectionService::performInspection([
  'status' => Request::getString('status'),
  'category' => Request::getString('category')
]);

$icons = [
  'warning' => 'common/warning',
  'ok' => 'common/success',
  'error' => 'common/stop'
];

foreach ($inspections as $inspection) {
  $entity = $inspection->getEntity();
  $writer->startRow();
  $writer->startCell(['icon'=>$icons[$inspection->getStatus()]]);
  $writer->text($inspection->getText());
  if ($inspection->getInfo()) {
    $writer->startLine(['minor'=>true])->text($inspection->getInfo())->endLine();
  }
  $writer->endCell();
  if ($entity) {
    $writer->startCell(['icon'=>$entity['icon']])->text($entity['title'])->endCell();
  } else {
    $writer->startCell()->endCell();
  }
  $writer->startCell()->text($inspection->getCategory())->endCell();
  $writer->startCell()->button(['text'=>'Fiks','data'=>['type'=>'pages']])->endCell();
  $writer->endRow();
}

$writer->endList();
?>