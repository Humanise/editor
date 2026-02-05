<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('partId');
$pageId = Request::getInt('pageId');
$type = Request::getString('partType');

$sectionData = Request::getObject('partSection');

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
      $part->setStyle(Request::getString('partStyle'));
      $part->save();

      PageService::markChanged($pageId);

      $context = DocumentTemplateController::buildPartContext($pageId);
      Response::html($ctrl->render($part, $context));
      exit;
    }
  }
}
Response::notFound();
?>