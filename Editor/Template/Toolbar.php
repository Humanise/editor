<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates
 */
require_once '../Include/Private.php';

$gui = '
<gui xmlns="uri:hui" title="Dokument">
  <controller url="toolbar.js"/>
  <script>
  controller.pageId=' . Request::getId() . ';
  </script>
  <tabs small="true" below="true">
    <tab title="' . Request::getString('title') . '" background="light">
      <toolbar>
        <icon icon="common/close" text="Luk" name="close"/>
        <divider/>
        <icon icon="common/internet" overlay="upload" text="Udgiv" name="publish" disabled="' . (PageService::isChanged(Request::getId()) ? 'false' : 'true') . '"/>
        <icon icon="common/view" text="Vis" name="preview"/>
        <icon icon="common/info" text="Info" name="properties"/>
      </toolbar>
    </tab>
  </tabs>
</gui>';
UI::render($gui);
?>