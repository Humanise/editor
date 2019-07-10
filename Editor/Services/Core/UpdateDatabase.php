<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Core
 */
require_once '../../Include/Public.php';

$username = Request::getString('username');
$password = Request::getString('password');

if (!AuthenticationService::isSuperUser($username,$password)) {
  Response::forbidden();
  exit;
}

$result = SchemaService::migrate();

Response::sendObject([
  'log' => join("\n", $result['log']),
  'success' => $result['success'],
  'updated' => !SchemaService::hasSchemaChanges()
]);
?>