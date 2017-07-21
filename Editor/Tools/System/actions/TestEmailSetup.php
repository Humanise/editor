<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.System
 */
require_once '../../../Include/Private.php';

$data = Request::getObject('data');
if (MailService::send($data->email,$data->name,$data->subject,$data->body)) {
  Response::sendObject(['success'=>true]);
} else {
  Response::sendObject(['success'=>false]);
}
?>