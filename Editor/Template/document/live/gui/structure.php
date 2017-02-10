<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../../Include/Private.php';

$gui = file_get_contents($basePath.'Editor/Template/document/live/gui/structure.ui.xml');

echo UI::renderFragment($gui);
?>