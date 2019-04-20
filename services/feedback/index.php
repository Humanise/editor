<?php
require_once '../../Editor/Include/Public.php';

$name = Request::getString('name');
$email = Request::getString('email');
$message = Request::getString('message');

if (Strings::isBlank($message)) {
  Response::badRequest();
  exit;
}

$feedback = new Feedback();
$feedback->setName($name);
$feedback->setEmail($email);
$feedback->setMessage($message);
$feedback->save();
$feedback->publish();

$body = "Besked fra " . $name . " (" . $email . ")\n\n" . $message;
MailService::sendToFeedback("Feedback fra website (" . $name . ")",$body);
?>