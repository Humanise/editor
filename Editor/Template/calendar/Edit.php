<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Calendar
 */
require_once '../../Include/Private.php';

$gui='
<frames xmlns="uri:hui">
	<frame url="../Toolbar.php?id='.Request::getId().'&amp;title=Kalender" scrolling="false" name="Toolbar"/>
	<frame url="Editor.php?id='.Request::getId().'" name="Frame"/>
	<script>
		hui.ui.tellContainers("changeSelection","service:edit");
	</script>
</frames>';

UI::render($gui);
?>