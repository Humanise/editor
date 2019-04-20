<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Calendar
 */
require_once '../../../Include/Private.php';

$id = Request::getId();
if ($row = CalendarTemplateController::load($id)) {
  Response::sendObject($row);
} else {
  Response::notFound();
}
?>