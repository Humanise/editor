<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Optimization
 */
require_once '../../Include/Private.php';

$gui='
<gui xmlns="uri:hui" padding="10" title="Issues">
  <controller url="controller.js"/>
  <controller url="status.js"/>

  <source name="listSource" url="data/ListIssues.php">
    <parameter key="windowPage" value="@list.window.page"/>
    <parameter key="type" value="@selector.value"/>
    <parameter key="kind" value="@kindSelector.value"/>
    <parameter key="status" value="@statusSelector.value"/>
    <parameter key="text" value="@search.value"/>
  </source>

  <source name="statusListSource" url="data/ListStates.php"/>

  <source name="sidebarSource" url="data/Sidebar.php"/>
  <source name="kindSelectorSource" url="data/SidebarKinds.php"/>
  <source name="statusSelectorSource" url="data/SidebarStates.php"/>
  <source name="statusSource" url="data/StatusItems.php"/>

  <structure>
    <top>
      <toolbar>
        <icon icon="common/note" overlay="new" text="Ny sag" name="addIssue"/>
        <divider/>
        <icon icon="common/info" text="Info" name="info" disabled="true"/>
        <icon icon="common/delete" text="{Delete; da:Slet}" name="delete" disabled="true">
          <confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete issue; da:Ja, slet sagen}" cancel="{No; da:Nej}"/>
        </icon>
        <item label="{Change type; da:Skift type}">
          <dropdown name="changeKind" placeholder="{Change type...;da:Skift type...}">
          '.UI::buildTranslatedOptions(IssueService::getKinds()).'
          </dropdown>
        </item>
        <right>
          <item label="{Search; da:Søgning}">
            <searchfield expanded-width="200" name="search"/>
          </item>
          <divider/>
          <icon icon="common/settings" text="{Settings; da:Indstillinger}" name="settings" click="pages.next()"/>
        </right>
      </toolbar>
    </top>
    <middle>
      <left>
        <overflow>
        <selection value="all" name="selector" top="5">
          <options source="sidebarSource"/>
        </selection>
        <selection value="any" name="statusSelector" top="5">
          <options source="statusSelectorSource"/>
        </selection>
        <selection value="any" name="kindSelector" top="5">
          <options source="kindSelectorSource"/>
        </selection>
        </overflow>
      </left>
      <center>
        <pages height="full" name="pages">
          <page key="list">
            <overflow>
              <list name="list" source="listSource" variant="light"/>
            </overflow>
          </page>
          <page key="settings" background="sand_grey">
            <box title="{Settings; da:Indstillinger}" width="400" top="20">
              <toolbar>
                <icon icon="common/object" overlay="new" text="{New status; da:Ny status}" name="newStatus"/>
              </toolbar>
              <list name="statusList" source="statusListSource" variant="light"/>
            </box>
          </page>
        </pages>
      </center>
    </middle>
    <bottom/>
  </structure>

  <window title="{Issue; da:Sag}" name="issueWindow" icon="common/folder" width="300" padding="10">
    <formula name="issueFormula">
      <fields labels="above">
        <field label="{Title; da:Titel}">
          <text-input key="title"/>
        </field>
        <field label="{Text; da:Tekst}">
          <text-input key="note" breaks="true"/>
        </field>
        <field label="Type">
          <dropdown key="kind">
            '.UI::buildTranslatedOptions(IssueService::getKinds()).'
          </dropdown>
        </field>
        <field label="Status">
          <dropdown key="statusId" source="statusSource"/>
        </field>
      </fields>
      <buttons>
        <button name="cancelIssue" text="{Cancel; da:Annuller}"/>
        <button name="deleteIssue" text="{Delete; da:Slet}">
          <confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete issue; da:Ja, slet sagen}" cancel="{No; da:Nej}"/>
        </button>
        <button name="saveIssue" text="{Save; da:Gem}" highlighted="true" submit="true"/>
      </buttons>
    </formula>
  </window>

  <window title="{Status; da:Status}" name="statusWindow" icon="common/folder" width="300" padding="10">
    <formula name="statusFormula">
      <fields labels="above">
        <field label="{Title; da:Titel}">
          <text-input key="title"/>
        </field>
      </fields>
      <buttons>
        <button name="cancelStatus" text="{Cancel; da:Annuller}"/>
        <button name="deleteStatus" text="{Delete; da:Slet}">
          <confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete; da:Ja, slet}" cancel="{No; da:Nej}"/>
        </button>
        <button name="saveStatus" text="{Save; da:Gem}" highlighted="true" submit="true"/>
      </buttons>
    </formula>
  </window>

</gui>';

UI::render($gui);
?>