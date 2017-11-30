<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.PageHistory
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');
$message = Request::getString('message');

PageService::updateHistoryMessage($id, $message);
?>