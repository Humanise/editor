<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';
$type = Request::getString('part_type');
$id = Request::getInt('id');
$sectionId = Request::getInt('section');

if ($section = DocumentSection::load($sectionId)) {
  $section->setTop(Request::getString('top'));
  $section->setBottom(Request::getString('bottom'));
  $section->setLeft(Request::getString('left'));
  $section->setRight(Request::getString('right'));
  $section->setFloat(Request::getString('float'));
  $section->setWidth(Request::getString('width'));
  $section->setClass(Request::getString('section_class'));
  $section->save();

  $controller = PartService::getController($type);
  if ($controller && method_exists($controller,'getFromRequest')) {
    $part = $controller->getFromRequest($id);
    $part->save();
    $controller->updateAdditional($part);
  }

  // Mark the page as changed
  PageService::markChanged($section->getPageId());
}

Response::redirect('../Editor.php?section=0');
?>