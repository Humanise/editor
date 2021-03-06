<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../Include/Private.php';

$maxUploadSize = UI::formatBytes(FileSystemService::getMaxUploadSize());

$gui = '
<gui xmlns="uri:hui" title="Billeder" padding="10" state="gallery">
  <controller url="controller.js"/>
  <controller url="groups.js"/>
  <controller url="upload.js"/>

  <source name="subsetSource" url="data/Selection.php"/>
  <source name="groupOptionsSource" url="../../Services/Model/Items.php?type=imagegroup"/>
  <source name="groupSource" url="data/GroupItems.php"/>
  <!--<source name="typesSource" url="TypeItems.php"/>-->

  <source name="imagesSource" url="data/GallerySource.php">
    <parameter key="text" value="@search.value"/>
    <parameter key="group" value="@groupSelection.value"/>
    <parameter key="subset" value="@subsetSelection.value"/>
  </source>

  <source name="listSource" url="data/ListImages.php">
    <parameter key="text" value="@search.value"/>
    <parameter key="subset" value="@subsetSelection.value"/>
    <parameter key="group" value="@groupSelection.value"/>
    <parameter key="windowPage" value="@list.window.page"/>
    <parameter key="sort" value="@list.sort.key"/>
    <parameter key="direction" value="@list.sort.direction"/>
  </source>

  <style>
  #photo {
    width: 150px; min-height: 50px; max-height: 300px; overflow: hidden; background: #fff no-repeat; font-size: 0; padding: 3px; border: 1px solid #ccc; border-color: #ddd #ccc #bbb; margin: 5px;
  }
  </style>

  <structure>
    <top>
      <toolbar>
        <icon icon="common/image" text="{Add image ; da:Tilføj billede}" overlay="upload" name="newFile"/>
        <icon icon="common/folder" text="{New group; da:Ny gruppe}" name="newGroup" overlay="new"/>
        <divider/>
        <icon icon="common/info" text="Info" name="info" disabled="true"/>
        <icon icon="common/delete" text="{Delete; da:Slet}" name="delete" disabled="true">
          <confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete image; da:Ja, slet billedet}" cancel="{No; da:Nej}"/>
        </icon>
        <icon icon="file/generic" text="{Download; da:Hent}" overlay="download" name="download" disabled="true"/>
        <icon icon="common/view" text="{View; da:Vis}" name="view" disabled="true"/>
        <divider/>
        <item label="{View; da:Visning}">
          <segmented value="gallery" name="viewSwitch">
            <option value="list" icon="view/list"/>
            <option value="gallery" icon="view/gallery"/>
          </segmented>
        </item>
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
            <options source="subsetSource" name="subsetSelection"/>
            <options source="groupSource" name="groupSelection" title="{Groups; da:Grupper}"/>
          </selection>
        </overflow>
      </left>
      <center>
        <bar name="groupBar" variant="layout" visible="false">
          <text name="groupTitle"/>
          <right>
            <button text="Info" small="true" name="groupInfo"/>
          </right>
        </bar>
        <overflow name="mainArea">
          <gallery name="gallery" source="imagesSource" padding="5" state="gallery" drop-files="true"/>
          <list name="list" source="listSource" state="list" drop-files="true"/>
        </overflow>
      </center>
    </middle>
    <bottom>
      <space top="1" right="8" align="right">
        <slider width="200" name="sizeSlider" value="0.5"/>
      </space>
    </bottom>
  </structure>

  <window title="{Group; da:Gruppe}" name="groupWindow" icon="common/folder" width="300" padding="10">
    <form name="groupFormula">
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
    </form>
  </window>

  <window title="{Addition of images; da:Tilføjelse af billeder}" name="uploadWindow" width="300">
    <tabs small="true" centered="true">
      <tab title="{Upload; da:Overførsel}" padding="10">
        <upload name="file" url="actions/UploadImage.php" widget="upload" multiple="true">
          <placeholder title="{Select images on your computer...; da:Vælg billeder på din computer...}" text="{The file can at most be ' . $maxUploadSize . ' large; da:Filen kan højest være ' . $maxUploadSize . ' stor}"/>
        </upload>
        <buttons align="center" top="10">
          <button name="cancelUpload" text="{Close; da:Luk}"/>
          <button name="upload" text="{Select images...; da:Vælg billeder...}" highlighted="true"/>
        </buttons>
      </tab>
      <tab title="{Fetch from the net; da:Hent fra nettet}" padding="10">
        <form name="fetchFormula">
          <fields labels="above">
            <field label="{Address; da:Adresse}:">
              <text-input key="url"/>
            </field>
          </fields>
        </form>
        <buttons align="center">
          <button name="cancelFetch" text="{Cancel; da:Annuller}"/>
          <button name="fetchImage" text="{Fetch; da:Hent}" highlighted="true"/>
        </buttons>
      </tab>
    </tabs>
  </window>

  <window title="{Image; da:Billede}" name="imageWindow" icon="common/image" width="450" padding="10">
    <form name="imageFormula">
      <columns flexible="true">
        <column width="180px">
          <div id="photo"></div>
        </column>
        <column>
          <fields labels="above">
            <field label="{Title; da:Titel}">
              <text-input key="title"/>
            </field>
            <field label="{Groups; da:Grupper}:">
              <checkboxes name="imageGroups" key="groups" max-height="200">
                <options source="groupOptionsSource"/>
              </checkboxes>
            </field>
          </fields>
        </column>
      </columns>
      <buttons top="5">
        <button name="cancelImage" text="{Cancel; da:Annuller}"/>
        <button name="deleteImage" text="{Delete; da:Slet}">
          <confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete image; da:Ja, slet billedet}" cancel="{Cancel; da:Annuller}"/>
        </button>
        <button name="saveImage" text="{Save; da:Gem}" highlighted="true" submit="true"/>
      </buttons>
    </form>
  </window>
</gui>';

UI::render($gui);
?>