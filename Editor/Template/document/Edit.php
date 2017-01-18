<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../Include/Private.php';

$gui='
<frames xmlns="uri:hui">
	<frame url="Toolbar.php" scrolling="false" name="Toolbar"/>
	<frame url="Editor.php" name="Frame"/>
	<script>
		hui.ui.tellContainers("changeSelection","service:edit");
	</script>
</frames>';

UI::render($gui);
?>
