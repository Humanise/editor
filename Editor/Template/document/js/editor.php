<?php
require_once '../../../Include/Private.php';
header('Content-type: text/javascript');

$controllers = PartService::getAllControllers();

foreach ($controllers as $controller) {
  if ($controller->isLiveEnabled()) {
    require_once '../../../Parts/' . $controller->getType() . '/live.js';
    echo "\n\n\n\n";
  }
}
require_once '../live/live.js';
?>