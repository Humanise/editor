<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Weblog
 */
require_once '../../Include/Private.php';

$gui = '
<gui xmlns="uri:hui" padding="10">
  <controller url="controller.js"/>
  <script>
    controller.id = ' . Request::getId() . ';
  </script>
  <box width="360" top="30" padding="10" title="Indstillinger til weblog">
    <form name="formula">
      <fields labels="above">
        <field label="Titel:">
          <text-input key="title"/>
        </field>
        <field label="Skabelon til ny side:">
          <dropdown key="blueprint">
            <option text="Ingen skabelon" value="0"/>
            ' . UI::buildOptions('pageblueprint') . '
          </dropdown>
        </field>
        <field label="Grupper">
          <checkboxes key="groups">
            ' . UI::buildOptions('webloggroup') . '
          </checkboxes>
        </field>
      </fields>
      <buttons>
        <button text="Opdater" name="save" highlighted="true" disabled="true"/>
      </buttons>
    </form>
  </box>
</gui>
';
UI::render($gui);
?>