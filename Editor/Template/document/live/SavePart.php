<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');
$pageId = Request::getInt('pageId');
$type = Request::getString('type');

$sectionData = Request::getObject('section');

if ($section = DocumentSection::load($id)) {

  if ($ctrl = PartService::getController($type)) {
    if ($part = $ctrl->getFromRequest($section->getPartId())) {
      $section->setTop($sectionData->top);
      $section->setBottom($sectionData->bottom);
      $section->setLeft($sectionData->left);
      $section->setRight($sectionData->right);
      $section->setFloat($sectionData->float);
      $section->setWidth($sectionData->width);
      $section->setClass($sectionData->class);
      $section->setStyle($sectionData->style);
      $section->save();
      $part->setStyle(Request::getString('style'));
      $part->save();

      PageService::markChanged($pageId);

      header("Content-Type: text/html; charset=UTF-8");
      $context = DocumentTemplateController::buildPartContext($pageId);
      echo $ctrl->render($part,$context);
      exit;
    }
  }
}
Response::notFound();
?>