<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Developer
 */
require_once '../../../Include/Private.php';

$writer = new ListWriter();

$writer->startList()->
  startHeaders()->
    header(['title' => 'Name', 'width' => 40])->
    header(['title' => 'Value'])->
  endHeaders();

$writer->startRow()->cell('Latest heart beat')->cell(Dates::formatFuzzy(SettingService::getLatestHeartBeat()))->endRow();
$writer->endList();
?>