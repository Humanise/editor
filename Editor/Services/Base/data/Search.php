<?php
/**
 * @package OnlinePublisher
 * @subpackage Customers
 */
require_once '../../../Include/Private.php';

$text = Request::getString('text');

$writer = new ListWriter();
$writer->startList();

if (Strings::isNotBlank($text)) {
  $list = PageQuery::rows()->withText($text)->asList();
  foreach ($list as $row) {

    $writer->startRow(['id' => $row['id'], 'kind' => 'page'])->
      startCell(['icon' => 'common/page'])->startWrap()->text($row['title'])->endWrap()->endCell()->
      startCell(['width' => 1])->
        startIcons()->
          icon(['icon' => 'monochrome/edit', 'revealing' => true, 'action' => true, 'data' => ['action' => 'view']])->
        endIcons()->
      endCell()->
      endRow();
  }
}
$writer->endList();
?>