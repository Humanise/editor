<?php
/**
 * @package OnlinePublisher
 * @subpackage Public.Services.Log
 */
require_once '../../../Editor/Include/Public.php';

$requestSecret = Request::getString('secret');
$secret = SettingService::getSharedSecret();

if (Strings::isBlank($secret) || Strings::isBlank($requestSecret) || $requestSecret !== $secret) {
  Response::forbidden();
  exit;
}


$feed = new Feed();
$feed->setTitle('OnlinePublisher log');
$feed->setDescription('OnlinePublisher log');
$feed->setPubDate(time());
$feed->setLastBuildDate(time());
$feed->setLink(ConfigurationService::getBaseUrl());

$entries = LogService::getEntries();
foreach ($entries->getList() as $row) {
  $item = new FeedItem();
  $item->setTitle($row['event'] . ': ' . $row['username']);
  $item->setDescription('USER: ' . $row['username'] . ' - ' . $row['message']);
  $item->setPubDate($row['time']);
  $item->setGuid(ConfigurationService::getBaseUrl() . $row['time']);
  $feed->addItem($item);
}

$serializer = new FeedSerializer();
$serializer->sendHeaders();
echo $serializer->serialize($feed);
?>