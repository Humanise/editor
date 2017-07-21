<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Base
 */
require_once '../../../Include/Private.php';


Response::sendObject([
  'unpublished' => PublishingService::getTotalUnpublishedCount()
]);

?>