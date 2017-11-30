<?php
/**
 * @package OnlinePublisher
 * @subpackage Sites
 */
require_once '../../../Include/Private.php';

$frameId = Request::getInt('frame');

$writer = new ItemsWriter();

$writer->startItems();
if ($frameId > 0) {
  $frame = Frame::load($frameId);
  $hierarchy = Hierarchy::load($frame->getHierarchyId());
  $writer->startItem([
    'icon' => 'common/hierarchy',
    'value' => $hierarchy->getId(),
    'title' => $hierarchy->getName(),
    'kind' => 'hierarchy'
  ]);
  $tree = HierarchyService::getTree($hierarchy->getId());
  encodeTreeLevel($tree,$writer);
  $writer->endItem();
}
$writer->endItems();

function encodeTreeLevel($items,&$writer) {
  foreach ($items as $item) {
    $writer->startItem(['icon' => $item['icon'], 'kind' => 'hierarchyItem', 'value' => $item['id'], 'title' => $item['title']]);
    encodeTreeLevel($item['children'],$writer);
    $writer->endItem();
  }
}
?>