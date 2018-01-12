<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');
$style = Request::getString('style');
$class = Request::getString('class');
$layout = Request::getString('layout');

$success = DocumentTemplateEditor::updateRow($id, [
  'style' => Request::getString('style'),
  'class' => Request::getString('class'),
  'layout' => Request::getString('layout')
]);

if (!$success) {
  Response::notFound();
}
?>