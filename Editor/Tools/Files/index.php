<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../Include/Private.php';

$maxUploadSize = GuiUtils::bytesToString(FileSystemService::getMaxUploadSize());
$gui = '
<gui xmlns="uri:hui" title="Filer" padding="10">
  <controller url="controller.js"/>
  <controller url="replace.js"/>
  <source name="groupSource" url="data/GroupItems.php"/>
  <source name="typesSource" url="data/TypeItems.php"/>
  <source name="filesSource" url="data/ListFiles.php">
    <parameter key="windowPage" value="@list.window.page"/>
    <parameter key="query" value="@search.value"/>
    <parameter key="group" value="@groupSelection.value"/>
    <parameter key="type" value="@typeSelection.value"/>
    <parameter key="main" value="@selector.value"/>
  </source>
  <structure>
    <top>
      <toolbar>
        <icon icon="file/generic" text="{Add file ; da:Tilføj fil}" overlay="upload" name="newFile"/>
        <icon icon="common/folder" text="{New group; da:Ny gruppe}" name="newGroup" overlay="new"/>
        <divider/>
        <icon icon="common/info" text="Info" name="info" disabled="true"/>
        <icon icon="common/delete" text="Slet" name="delete" disabled="true">
          <confirm text="Er du sikker?" ok="Ja, slet filen" cancel="Annuller"/>
        </icon>
        <icon icon="file/generic" text="{Download; da:Hent}" overlay="download" name="download" disabled="true"/>
        <icon icon="file/generic" text="{Replace; da:Erstat}" name="replace" overlay="change" disabled="true"/>
        <icon icon="common/view" text="{Show; da:Vis}" name="view" disabled="true"/>
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
          <options source="groupSource" name="groupSelection" title="{Groups; da:Grupper}"/>
          <options source="typesSource" name="typeSelection" title="{Types; da:Typer}"/>
        </selection>
        </overflow>
      </left>
      <center>
        <overflow>
          <list name="list" source="filesSource" drop-files="true"/>
        </overflow>
      </center>
    </middle>
    <bottom/>
  </structure>


  <window title="{Addition of new file; da:Tilføjelse af ny fil}" name="uploadWindow" width="300">
    <tabs small="true" centered="true">
      <tab title="{Upload; da:Overførsel}" padding="10">
        <upload name="file" url="actions/UploadFile.php" widget="upload" multiple="true">
          <placeholder title="{Select files on your computer; da:Vælg filer på din computer...}" text="{The file can at most be ' . $maxUploadSize . ' large; da:Filen kan højest være ' . $maxUploadSize . ' stor}"/>
        </upload>
        <buttons align="center" top="10">
          <button name="cancelUpload" text="{Close; da:Luk}"/>
          <button name="upload" text="{Select files...; da:Vælg filer...}" highlighted="true"/>
        </buttons>
      </tab>
      <tab title="{Fetch from the net; da:Hent fra nettet}" padding="10">
        <formula name="fetchFormula">
          <field label="{Address; da:Adresse}:">
            <text-input key="url"/>
          </field>
          <buttons>
            <button name="fetchFile" text="{Fetch; da:Hent}" highlighted="true"/>
          </buttons>
        </formula>
      </tab>
    </tabs>
  </window>


  <window title="{Replacement of file; da:Erstatning af fil}" name="replaceWindow" width="300" padding="10">
    <upload name="replaceFile" url="actions/ReplaceFile.php" widget="replaceUpload">
      <placeholder title="{Select a new file on your computer; da:Vælg en ny fil på din computer...}" text="{The file can at most be ' . $maxUploadSize . ' large; da:Filen kan højest være ' . $maxUploadSize . ' stor}"/>
    </upload>
    <buttons align="center" top="10">
      <button name="cancelReplaceUpload" text="{Cancel; da:Annuller}"/>
      <button name="replaceUpload" text="{Select file...; da:Vælg fil...}" highlighted="true"/>
    </buttons>
  </window>

  <window title="{Group; da:Gruppe}" name="groupWindow" icon="common/folder" width="300" padding="5">
    <formula name="groupFormula">
      <fields labels="above">
        <field label="{Title; da:Titel}">
          <text-input key="title"/>
        </field>
      </fields>
      <buttons>
        <button name="cancelGroup" text="{Cancel; da:Annuller}"/>
        <button name="deleteGroup" text="{Delete; da:Slet}">
          <confirm text="{Are yu sure?; da:Er du sikker?}" ok="{Yes, delete group; da:Ja, slet gruppen}" cancel="{No; da:Nej}"/>
        </button>
        <button name="saveGroup" text="{Save; da:Gem}" highlighted="true"/>
      </buttons>
    </formula>
  </window>

  <window title="{File; da:Fil}" name="fileWindow" icon="file/generic" width="300" padding="5">
    <formula name="fileFormula">
      <fields labels="above">
        <field label="{Title; da:Titel}">
          <text-input key="title"/>
        </field>
        <field label="{Groups; da:Grupper}:">
          <checkboxes name="fileGroups" key="groups">
            <options source="groupSource"/>
          </checkboxes>
        </field>
      </fields>
      <buttons>
        <button name="cancelFile" text="{Cancel; da:Annuller}"/>
        <button name="deleteFile" text="{Delete; da:Slet}">
          <confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete file; da:Ja, slet filen}" cancel="{Cancel; da:Annuller}"/>
        </button>
        <button name="updateFile" text="{Save; da:Gem}" highlighted="true" submit="true"/>
      </buttons>
    </formula>
  </window>
</gui>';

UI::render($gui);
?>