<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Include/Private.php';



$writer = new ListWriter();

$writer->startList()->
  startHeaders()->
    header(['title' => ['Table', 'da' => 'Tabel'], 'width' => 30])->
    header(['title' => ['Columns', 'da' => 'Kolonner'], 'width' => 70])->
  endHeaders();

$tables = SchemaService::getTables();
foreach ($tables as $table) {
  $columns = SchemaService::getTableColumns($table);
  $writer->startRow()->
    cell($table)->
    cell(count($columns))->
  endRow();
}

$writer->endList();
?>