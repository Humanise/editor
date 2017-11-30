<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.PageHistory
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');

Response::sendObject([
  'message' => PageService::getHistoryMessage($id)
]);
?>