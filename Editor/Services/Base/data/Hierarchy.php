<?php
/**
 * @package OnlinePublisher
 * @subpackage Sites
 */
require_once '../../../Include/Private.php';

$frameId = Request::getInt('frame');
if ($frameId > 0) {
  $frame = Frame::load($frameId);
  $hierarchies = [Hierarchy::load($frame->getHierarchyId())];
} else {
  $hierarchies = Hierarchy::search();
}

$icons = ['page' => 'common/page', 'pageref' => 'common/pagereference', 'email' => 'common/email', 'url' => 'monochrome/globe', 'file' => 'monochrome/file'];

$writer = new ItemsWriter();

$writer->startItems();
foreach ($hierarchies as $hierarchy) {
  $title = $hierarchy->getName();
  if ($hierarchy->getChanged() > $hierarchy->getPublished()) {
    $title .= ' !';
  }
  $tree = HierarchyService::getTree($hierarchy->getId());
  $writer->startItem([
    'icon' => 'common/hierarchy',
    'value' => $hierarchy->getId(),
    'title' => $title,
    'kind' => 'hierarchy'
  ]);
  encodeTreeLevel($tree, $writer);
  $writer->endItem();
}
$writer->endItems();

function encodeTreeLevel($items,&$writer) {
  foreach ($items as $item) {
    $writer->startItem(['icon' => $item['icon'], 'kind' => $item['target']['type'], 'value' => $item['target']['id'], 'title' => $item['title']]);
    encodeTreeLevel($item['children'],$writer);
    $writer->endItem();
  }
}
?>