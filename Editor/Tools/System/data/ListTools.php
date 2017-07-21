<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Include/Private.php';

$available = ToolService::getAvailable();
$installed = ToolService::getInstalled();

$writer = new ListWriter();

$writer->startList(['unicode'=>true])->
  startHeaders()->
    header(['title'=>['Tool', 'da'=>'Værktøj'], 'width'=>40])->
    header(['title'=>['Key', 'da'=>'Nøgle'], 'width'=>30])->
    header(['title'=>'', 'width'=>1])->
  endHeaders();

foreach ($available as $key) {
  $info = ToolService::getInfo($key);
  $writer->
  startRow(['kind'=>'tool', 'id'=>$key])->
    startCell(['icon'=>$info ? $info->icon : 'common/tools']);
    if ($info) {
      $writer->startLine()->text($info ? $info->name : $key)->endLine()->
        startLine(['dimmed'=>true])->text($info ? $info->description : $key)->endLine();
    } else {
      $writer->text($key);
    }
    $writer->endCell()->
    startCell()->text($key)->endCell();
    if (in_array($key,$installed)) {
      $writer->startCell()->
        button(['text'=>['Remove', 'da'=>'Fjern'], 'data'=>['action'=>'uninstallTool', 'key'=>$key]])->
      endCell();
    } else {
      $writer->startCell()->
        button(['text'=>['Add', 'da'=>'Tilføj'], 'data'=>['action'=>'installTool', 'key'=>$key]])->
      endCell();
    }
  $writer->endRow();
}
$writer->endList();
?>