<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Html
 */
require_once '../../../Include/Private.php';

$id = Request::getId();

HtmlTemplateController::convertToDocument($id);

?>