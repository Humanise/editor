<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');
$type = Request::getString('type');

if ($section = DocumentSection::load($id)) {
  if ($part = PartService::load($type, $section->getPartId())) {
    Response::sendObject([
      'part' => $part,
      'section' => $section
    ]);
    exit;
  }
}
Response::notFound();
?>