<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Optimization
 */
require_once '../../../Include/Private.php';

$id = Request::getId();

$phrase = Testphrase::load($id);

$writer = new ListWriter();

$writer->startList()->
  startHeaders()->
    header(['title' => ['Page', 'da' => 'Side']])->
    header(['width' => 1])->
  endHeaders();

$list = PageQuery::rows()->withContentText($phrase->getTitle())->asList();

foreach ($list as $row) {
  $writer->startRow(['id' => $row['id'], 'kind' => 'page'])->
    startCell(['icon' => 'common/page'])->
      text($row['title'])->
    endCell()->
    startCell()->
      startIcons()->
        icon(['icon' => 'monochrome/view', 'action' => true, 'revealing' => true, 'data' => ['action' => 'view']])->
        icon(['icon' => 'monochrome/edit', 'action' => true, 'revealing' => true, 'data' => ['action' => 'edit']])->
      endIcons()->
    endCell()->
  endRow();
}

$writer->endList();
?>