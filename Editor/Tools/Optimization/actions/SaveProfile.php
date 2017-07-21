<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Optimization
 */
require_once '../../../Include/Private.php';

$settings = OptimizationService::getSettings();

$name = Request::getString('name');
$url = Request::getString('url');

if (!is_object($settings)) {
  $settings = new stdClass();
}

if (!is_array(@$settings->profiles)) {
  $settings->profiles = [];
}

$settings->profiles[] = ['name'=>$name, 'url'=>$url];

OptimizationService::setSettings($settings);
?>