<?php
require_once '../../Editor/Include/Public.php';

if (ConfigurationService::isPublicSession()) {
  session_set_cookie_params(0);
  session_start();
}

StatisticsService::registerPage([
  'id' => Request::getInt('page'),
  'referrer' => Request::getString('referrer'),
  'uri' => Request::getString('uri')
]);
?>