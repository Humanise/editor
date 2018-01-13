<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$columnId = Request::getInt('column');
$index = Request::getInt('index');

$clipboard = ClipboardService::getClipboard();

if ($clipboard && $clipboard['type'] == 'section') {
  $section = DocumentTemplateEditor::getSection($clipboard['id']);
  $part = PartService::load($section['part_type'],$section['part_id']);
  if ($part) {
    $part->setId(null);
    $part->save();
    $sectionId = DocumentTemplateEditor::addSectionFromPart($columnId,$index,$part);

    if ($clipboard['action'] == 'cut') {
      DocumentTemplateEditor::deleteSection($clipboard['id']);
      ClipboardService::clear();
    }
    Response::sendObject(['sectionId' => $sectionId]);
    exit;
  }
}

Response::badRequest();


?>