<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Developer
 */
require_once '../../../Include/Private.php';



Response::sendObject([
  'simulateLatency' => isset($_SESSION['core.debug.simulateLatency']) && $_SESSION['core.debug.simulateLatency']==true,
  'logDatabaseQueries' => isset($_SESSION['core.debug.logDatabaseQueries']) && $_SESSION['core.debug.logDatabaseQueries']==true
]);
?>