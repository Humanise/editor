<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');

$success = DocumentTemplateEditor::updateColumn($id, [
  'style' => Request::getString('style'),
  'class' => Request::getString('class')
]);

if (!$success) {
  Response::notFound();
}
?>