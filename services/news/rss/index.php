<?php
/**
 * @package OnlinePublisher
 * @subpackage Public.Services.Log
 */

require_once '../../../Editor/Include/Public.php';

$group = Request::getInt('group');
if ($group > 0) {
  $feed = NewsService::buildFeed($group);
  if ($feed) {
    $serializer = new FeedSerializer();
    $serializer->sendHeaders();
    echo $serializer->serialize($feed);
  } else {
    Response::notFound();
  }
} else {
  Response::badRequest();
}
?>