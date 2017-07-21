<?php
/**
 * @package OnlinePublisher
 * @subpackage Services
 */
require_once '../../Include/Public.php';

if (Request::isPost()) {

  $text = Request::getString('text');
  if ($user = AuthenticationService::getUserByEmailOrUsername($text)) {
    if (!$user->getInternal()) {
      Response::sendObject(['success' => false,'message' => 'Brugeren har ikke adgang']);
      exit;
    } else if (!Strings::isBlank($user->getEmail())) {
      if (AuthenticationService::createValidationSession($user)) {
        Response::sendObject(['success' => true]);
        exit;
      } else {
        Response::sendObject(['success' => false,'message' => 'Det lykkedes ikke at sende e-mail']);
        exit;
      }
    } else {
      Response::sendObject(['success' => false,'message' => 'Brugeren har ingen e-mail']);
      exit;
    }
  } else {
    Response::sendObject(['success' => false,'message' => 'Brugeren blev ikke fundet']);
    exit;
  }

} else {
  Response::sendObject(['success' => false,'message' => 'Invalid request']);
}
exit;
?>