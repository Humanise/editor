<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../Include/Private.php';

$move = Request::getInt('move',0);
$targetItem = Request::getInt('targetItem',0);
$targetHierarchy = Request::getInt('targetHierarchy',0);

if ($move == 0 || ($targetItem == 0 && $targetHierarchy == 0)) {
  Response::badRequest();
  exit;
}

$response = Hierarchy::relocateItem($move,$targetItem,$targetHierarchy);

Response::sendObject($response);
?>