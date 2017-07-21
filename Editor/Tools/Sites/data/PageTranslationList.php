<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('page');

$writer = new ListWriter();

$writer->startList()->
  startHeaders()->
    header(['title'=>'Side'])->
    header(['title'=>'Sprog','width'=>1])->
    header(['width'=>1])->
  endHeaders();

$list = PageService::getPageTranslationList($id);

foreach ($list as $row) {
  $writer->
  startRow([ 'kind'=>'translation', 'id'=>$row['id'] ])->
    startCell(['icon'=>'common/page'])->text($row['title'])->endCell()->
    startCell()->icon(['icon'=>GuiUtils::getLanguageIcon($row['language'])])->endCell()->
    startCell()->
      startIcons()->
        icon(['icon'=>'monochrome/delete','action'=>true,'data'=>['action'=>'delete']])->
      endIcons()->
    endCell()->
  endRow();
}
$writer->endList();
?>