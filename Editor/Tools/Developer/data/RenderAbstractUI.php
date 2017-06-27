<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Developer
 */
require_once '../../../Include/Private.php';

$xml = Request::getString('xml');
echo UI::buildAbstractUI($xml);
?>