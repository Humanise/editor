<?php
/**
 * @package OnlinePublisher
 * @subpackage Sites
 */
require_once '../../../Include/Private.php';

$frameId = Request::getInt('frame');
$hierarchies = [];
if ($frameId > 0) {
  $frame = Frame::load($frameId);
  if ($hierarchy = Hierarchy::load($frame->getHierarchyId())) {
    $hierarchies[] = $hierarchy;
  }
} else {
  $hierarchies = Hierarchy::search();
}

$writer = new ItemsWriter();

$writer->startItems();

foreach ($hierarchies as $hierarchy) {
  $title = $hierarchy->getName();
  if ($hierarchy->getChanged() > $hierarchy->getPublished()) {
    $title .= ' !';
  }
  $tree = HierarchyService::getTree($hierarchy->getId());
  $writer->startItem(['icon' => 'common/hierarchy', 'kind' => 'hierarchy', 'value' => $hierarchy->getId(), 'title' => $title]);
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