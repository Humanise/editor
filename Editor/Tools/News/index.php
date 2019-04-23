<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../Include/Private.php';

$maxUploadSize = GuiUtils::bytesToString(FileSystemService::getMaxUploadSize());

$blueprints = PageService::getBlueprintsByTemplate('document');
$blueprintItems = UI::buildOptions($blueprints);

$gui = '
<gui xmlns="uri:hui" title="{News; da:Nyheder}" padding="10">
  <controller url="controller.js"/>
  <controller url="sources.js"/>
  <source name="pageItems" url="../../Services/Model/Items.php?type=page"/>
  <source name="fileItems" url="../../Services/Model/Items.php?type=file"/>
  <source name="groupSource" url="data/GroupItems.php"/>
  <source name="sourcesSource" url="../../Services/Model/Items.php?type=newssource"/>
  <source name="newsSource" url="data/ListNews.php">
    <parameter key="windowPage" value="@list.window.page"/>
    <parameter key="sort" value="@list.sort.key"/>
    <parameter key="direction" value="@list.sort.direction"/>
    <parameter key="query" value="@search.value"/>
    <parameter key="group" value="@groupSelection.value"/>
    <parameter key="main" value="@selector.value"/>
    <parameter key="source" value="@sourceSelection.value"/>
  </source>

  <structure>
    <top>
      <toolbar>
        <icon icon="common/news" text="{New news item; da:Ny nyhed}" name="newNews" overlay="new"/>
        ' . ($blueprintItems ? '<icon icon="common/page" text="{New article; da:Ny artikel}" name="newArticle" overlay="new"/>' : '') . '
        <icon icon="common/folder" text="{New group; da:Ny gruppe}" name="newGroup" overlay="new"/>
        <icon icon="common/internet" text="{New source; da:Ny kilde}" name="newSource" overlay="new"/>
        <divider/>
        <icon icon="common/info" text="Info" name="info" disabled="true"/>
        <icon icon="common/delete" text="{Delete; da:Slet}" name="delete" disabled="true">
          <confirm text="{Are yu sure?; da:Er du sikker?}" ok="{Yes, delete news; da:Ja, slet nyheden}" cancel="{Cancel; da:Annuller}"/>
        </icon>
        <icon icon="common/duplicate" text="{Duplicate; da:Dubler}" name="duplicate" disabled="true"/>
        <right>
          <item label="{Search; da:Søgning}">
            <searchfield name="search" expanded-width="200"/>
          </item>
        </right>
      </toolbar>
    </top>
    <middle>
      <left>
        <overflow>
        <selection value="all" name="selector" top="5">
          <option icon="common/files" text="{All; da:Alle}" value="all"/>
          <option icon="common/time" text="{Latest 24 hours; da:Seneste døgn}" value="latest"/>
          <option icon="common/play" text="{Active; da:Aktive}" value="active"/>
          <option icon="common/stop" text="{Inactive; da:Inaktive}" value="inactive"/>
          <title>Links</title>
          <option icon="monochrome/globe" text="{External; da:Eksterne}" value="url"/>
          <option icon="monochrome/file" text="{Page; da:Sider}" value="page"/>
          <option icon="monochrome/file" text="{Files; da:Filer}" value="file"/>
          <option icon="monochrome/email" text="{E-mail; da:E-post}" value="email"/>
          <options source="groupSource" name="groupSelection" title="{Groups; da:Grupper}"/>
          <options source="sourcesSource" name="sourceSelection" title="{Sources; da:Kilder}"/>
        </selection>
        </overflow>
      </left>
      <center>
        <bar variant="layout" state="source">
          <text name="sourceHeader" variant="header"/>
          <text name="sourceText"/>
          <right>
            <button text="Info" name="sourceInfo" small="true"/>
            <button text="{Synchronize; da:Synkroniser}" name="synchronize" small="true"/>
          </right>
        </bar>
        <bar variant="layout" state="group">
          <text name="groupHeader" variant="header"/>
          <right>
            <button text="Info" name="groupInfo" small="true"/>
            <button text="{RSS-address; da:RSS-adresse}" name="groupRSS" small="true"/>
          </right>
        </bar>
        <overflow>
          <list name="list" source="newsSource"/>
        </overflow>
      </center>
    </middle>
    <bottom/>
  </structure>

  <window title="{Group; da:Gruppe}" name="groupWindow" icon="common/folder" width="300" padding="10">
    <formula name="groupFormula">
      <fields labels="above">
        <field label="{Title; da:Titel}">
          <text-input key="title"/>
        </field>
      </fields>
      <buttons top="5">
        <button name="cancelGroup" text="{Cancel; da:Annuller}"/>
        <button name="deleteGroup" text="{Delete; da:Slet}">
          <confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete group; da:Ja, slet gruppen}" cancel="{No; da:Nej}"/>
        </button>
        <button name="saveGroup" text="{Save; da:Gem}" highlighted="true"/>
      </buttons>
    </formula>
  </window>

  <window title="{Source; da:Kilde}" name="sourceWindow" icon="common/internet" width="300" padding="10">
    <formula name="sourceFormula">
      <fields labels="above">
        <field label="{Title; da:Titel}">
          <text-input key="title"/>
        </field>
        <field label="{Address; da:Adresse}">
          <text-input key="url"/>
        </field>
        <field label="Interval ({seconds; da:sekunder})" hint="1 time = 3600 sekunder">
          <number-input key="syncInterval"/>
        </field>
      </fields>
      <buttons top="5">
        <button name="cancelSource" text="{Cancel; da:Annuller}"/>
        <button name="deleteSource" text="{Delete; da:Slet}">
          <confirm text="{Are you sure? da:Er du sikker?}" ok="{Yes, delete source; da:Ja, slet kilden}" cancel="{No; da:Nej}"/>
        </button>
        <button submit="true" text="{Save; da:Gem}" highlighted="true"/>
      </buttons>
    </formula>
  </window>


  <window title="{News; da:Nyhed}" name="newsWindow" variant="news" icon="common/news" width="450">

    <tabs small="true" centered="true">

      <tab title="{News; da:Nyhed}" padding="10">
        <formula name="newsFormula">
          <fields labels="above">
            <field label="{Title; da:Titel}">
              <text-input key="title"/>
            </field>
            <field label="Note">
              <text-input key="note" breaks="true"/>
            </field>
          </fields>
          <columns flexible="true" space="10">
            <column>
              <field label="{From; da:Fra}">
                <datetime-input key="startdate" return-type="seconds"/>
              </field>
            </column>
            <column>
              <field label="{To; da:Til}">
                <datetime-input key="enddate" return-type="seconds"/>
              </field>
            </column>
          </columns>
          <fields labels="above">
            <field label="{Groups; da:Grupper}:">
              <checkboxes name="newsGroups">
                <options source="groupSource"/>
              </checkboxes>
            </field>
          </fields>
        </formula>
      </tab>

      <tab title="Links">
        <toolbar centered="true">
          <icon text="{New link; da:Nyt link}" icon="common/link" overlay="new" click="newsLinks.addLink()"/>
        </toolbar>
        <space bottom="10">
          <links name="newsLinks" page-source="pageItems" file-source="fileItems"/>
        </space>
      </tab>

    </tabs>

    <buttons right="10" bottom="10" align="right">
      <button name="cancelNews" text="{Cancel; da:Annuller}"/>
      <button name="deleteNews" text="{Delete; da:Slet}">
        <confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete news; da:Ja, slet nyheden}" cancel="{Cancel; da:Annuller}"/>
      </button>
      <button name="updateNews" text="{Save; da:Gem}" highlighted="true"/>
    </buttons>

  </window>


  <box title="{New article; da:Ny artikel}" name="newArticleBox" absolute="true" padding="10" modal="true" width="636" variant="textured" closable="true">
    <formula name="articleFormula">
      <fields labels="above">
        <field label="{Title; da:Titel}">
          <text-input key="title"/>
        </field>
        <field label="{Summary; da:Opsummering}">
          <text-input key="summary" breaks="true"/>
        </field>
        <field label="{Text; da:Tekst}">
          <text-input key="text" breaks="true"/>
        </field>
        <field label="Link">
          <text-input key="linkText"/>
        </field>
        <field label="{Template; da:Skabelon}">
          <dropdown key="blueprint" name="articleBlueprint">' . $blueprintItems . '</dropdown>
        </field>
      </fields>
      <columns flexible="true">
        <column>
          <field label="{From; da:Fra}">
            <datetime-input key="startdate"/>
          </field>
        </column>
        <column>
          <field label="{To; da:Til}">
            <datetime-input key="enddate"/>
          </field>
        </column>
      </columns>
      <fields labels="above">
        <field label="{Groups; da:Grupper}:">
          <checkboxes key="groups">
            <options source="groupSource"/>
          </checkboxes>
        </field>
      </fields>
      <buttons>
        <button name="cancelNewArticle" text="{Cancle; da:Annuller}" click="newNewsBox.hide()"/>
        <button name="createNewArticle" text="{Create; da:Opret}" highlighted="true" submit="true"/>
      </buttons>
    </formula>
  </box>
</gui>';

UI::render($gui);
?>