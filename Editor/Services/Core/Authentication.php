<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Core
 */
require_once '../../Include/Public.php';

if (Request::isPost()) {
  $page = Request::getInt('page');
  $username = Request::getString('username');
  $password = Request::getString('password');

  if (InternalSession::logIn($username, $password)) {
    ToolService::install('System'); // Ensure that the system tool is present
    Response::sendObject(['success' => true]);
    exit;
  } else {
    Log::warn("Failed login attempt for user: " . $username);
  }
} else {
  Log::warn("Login attempt with non-POST request");
}
usleep(rand(1500000, 3000000));  // Wait for random amount of time
Response::sendObject(['success' => false]);
?>