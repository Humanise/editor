<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Include/Private.php';

$available = TemplateService::getAvailableTemplates();
$installed = TemplateService::getInstalledTemplateKeys();
$used = TemplateService::getUsedTemplates();

$writer = new ListWriter();

$writer->startList(['unicode'=>true])->
  startHeaders()->
    header(['title'=>['Template', 'da'=>'Skabelon'], 'width'=>40])->
    header(['title'=>['Key', 'da'=>'Nøgle'], 'width'=>30])->
    header(['title'=>['Used', 'da'=>'Anvendt'], 'width'=>30])->
    header(['title'=>'', 'width'=>1])->
  endHeaders();

$designs = Query::after('design')->get();
foreach ($available as $key) {
  $info = TemplateService::getTemplateInfo($key);
  $writer->
  startRow(['kind'=>'template', 'id'=>$key])->
    startCell(['icon'=>'common/page'])->
      startLine()->text($info['name'])->endLine()->
      startLine(['dimmed'=>true])->text($info['description'])->endLine()->
    endCell()->
    startCell()->text($key)->endCell();
    if (in_array($key,$installed)) {
      $writer->startCell()->icon(['icon'=>in_array($key,$used) ? 'common/success' : 'common/stop'])->endCell();
      if (!in_array($key,$used)) {
        $writer->startCell()->
          button(['text'=>['Uninstall', 'da'=>'Afinstallér'], 'data'=>['action'=>'uninstallTemplate', 'key'=>$key]])->
        endCell();
      } else {
        $writer->startCell()->endCell();
      }
    } else {
      $writer->startCell()->endCell();
      $writer->startCell()->
        button(['text'=>['Install', 'da'=>'Installér'], 'data'=>['action'=>'installTemplate', 'key'=>$key]])->
      endCell();
    }
  $writer->endRow();
}
$writer->endList();
?>