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
    header(['title' => ['Columns', 'da' => 'Kolonner'], 'width' => 20])->
    header(['title' => ['Status', 'da' => 'Status'], 'width' => 50])->
  endHeaders();

$tables = DatabaseUtil::getTables();
foreach ($tables as $table) {
  $columns = DatabaseUtil::getTableColumns($table);
  $errors = DatabaseUtil::checkTable($table,$columns);
  $writer->startRow()->
    cell($table)->
    cell(count($columns))->
    cell(implode("\n",$errors))->
  endRow();
}

$missingTables = DatabaseUtil::findMissingTables($tables);
foreach ($missingTables as $table) {
  $writer->startRow()->
    cell($table)->
    cell('?')->
    cell(['The table is missing', 'da' => 'Tabellen mangler'])->
  endRow();
}
$writer->endList();
?>