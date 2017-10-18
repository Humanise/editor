<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');
$style = Request::getString('style');

Response::sendObject([
  'css' => DocumentTemplateController::renderRowStyle($id, $style)
])
?>