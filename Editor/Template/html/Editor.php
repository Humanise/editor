<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.HTML
 */
require_once '../../Include/Private.php';

$gui = '
<gui xmlns="uri:hui" padding="10">
  <controller url="controller.js"/>
  <script>
    controller.id = ' . Request::getId() . ';
  </script>
  <box width="800" top="10" padding="10" title="HTML">
    <form name="formula">
      <fields labels="above">
        <field label="Titel:">
          <text-input key="title"/>
        </field>
        <field label="HTML:">
          <code-input key="html"/>
        </field>
      </fields>
      <buttons>
        <button text="{Convert to document; da:Konverter til dokument}" name="convert"/>
        <button text="{Update; da:Opdater}" name="save" highlighted="true" disabled="true" submit="true"/>
      </buttons>
    </form>
  </box>
</gui>
';
UI::render($gui);
?>