<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Base
 */
require_once '../../../Include/Private.php';

$subset = Request::getString('subset');
$query = [$subset => true];

$list = ReviewService::search($query);

$writer = new ListWriter();

$writer->startList()->
  startHeaders()->
    header(['title' => 'Side', 'width' => 45])->
  endHeaders();

foreach ($list as $review) {
  $writer->startRow([ 'kind' => 'page', 'id' => $review->getPageId() ])->
    startCell(['icon' => 'common/page'])->
      text($review->getPageTitle())->
    endCell()->
  endRow();
}

$writer->endList();

?>