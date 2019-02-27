<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Calendar
 */
require_once '../../../Include/Private.php';

$data = Request::getObject('data');
if ($data) {
  CalendarTemplateController::update($data);
}
?>