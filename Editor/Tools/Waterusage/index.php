<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Waterssage
 */
require_once '../../Include/Private.php';

$maxUploadSize = GuiUtils::bytesToString(FileSystemService::getMaxUploadSize());

$gui='
<gui xmlns="uri:hui" title="Vandforbrug" padding="10" state="list">
  <controller url="controller.js?v=1"/>
  <source name="listSource" url="data/List.php">
    <parameter key="windowPage" value="@list.window.page"/>
    <parameter key="sort" value="@list.sort.key"/>
    <parameter key="direction" value="@list.sort.direction"/>
    <parameter key="query" value="@search.value"/>
    <parameter key="filter" value="@selector.value"/>
    <parameter key="filterKind" value="@selector.kind"/>
  </source>
  <source name="filterSource" url="data/FilterItems.php"/>
  <structure>
    <top>
      <toolbar>
        <icon icon="file/generic" text="Importér" overlay="upload" name="import"/>
        <icon icon="file/generic" text="Eksportér" overlay="download" name="exportIcon"/>
        <divider/>
        <icon icon="common/gauge" text="Ny måler" overlay="new" name="newMeter"/>
        <icon icon="common/water" text="Ny aflæsning" overlay="new" name="newUsage"/>
        <right>
          <item label="Søgning">
            <searchfield name="search" expanded-width="200"/>
          </item>
        </right>
      </toolbar>
    </top>
    <middle>
      <left>
        <overflow>
        <selection value="meters" name="selector">
          <option icon="common/gauge" text="Målere" value="meters"/>
          <option icon="file/generic" text="Log" value="log"/>
          <options source="filterSource"/>
        </selection>
        </overflow>
      </left>
      <center>
        <overflow>
          <list name="list" source="listSource" state="list"/>
          <fragment state="meter" height="full" background="linen">
            <bar variant="layout">
              <button text="Luk" icon="common/close" name="closeMeter"/>
            </bar>
            <space all="20">
              <columns flexible="true" space="20">
                <column width="300px">
                  <box variant="rounded" padding="10">
                  <formula name="summaryFormula">
                    <fields labels="above">
                      <field label="Nummer">
                        <text-input key="number"/>
                      </field>
                      <field label="Gade">
                        <text-input key="street"/>
                      </field>
                    </fields>
                    <columns flexible="true">
                      <column width="100px">
                        <fields labels="above">
                          <field label="Postnummer">
                            <text-input key="zipcode"/>
                          </field>
                        </fields>
                      </column>
                      <column>
                        <fields labels="above">
                          <field label="By">
                            <text-input key="city"/>
                          </field>
                        </fields>
                      </column>
                    </columns>
                    <fields labels="above">
                      <field label="E-post">
                        <text-input key="email"/>
                      </field>
                      <field label="Telefon">
                        <text-input key="phone"/>
                      </field>
                    </fields>
                    <buttons>
                      <button name="deleteMeter" text="Slet">
                        <confirm text="Er du sikker?" ok="Ja, slet" cancel="Nej"/>
                      </button>
                      <button text="Opdater" submit="true" name="saveMeter" highlighted="true"/>
                    </buttons>
                    </formula>
                  </box>
                </column>
                <column>
                  <box variant="rounded">
                    <bar>
                      <button text="Tilføj aflæsning" name="addSubUsage" icon="common/new"/>
                    </bar>
                    <overflow height="200">
                      <list name="subUsageList"/>
                    </overflow>
                  </box>
                </column>
              </columns>
            </space>
          </fragment>
        </overflow>
      </center>
    </middle>
    <bottom/>
  </structure>

  <window title="Aflæsning" icon="common/water" name="subUsageWindow" width="300" padding="5">
    <formula name="subUsageFormula">
      <fields labels="above">
        <field label="Værdi">
          <number-input key="value" max="1000000000"/>
        </field>
        <field label="Tidspunkt">
          <datetime-input key="date" return-type="seconds"/>
        </field>
      </fields>
      <buttons>
        <button name="cancelSubUsage" text="Annuller"/>
        <button name="deleteSubUsage" text="Slet">
          <confirm text="Er du sikker?" ok="Ja, slet" cancel="Nej"/>
        </button>
        <button name="saveSubUsage" text="Gem" highlighted="true" submit="true"/>
      </buttons>
    </formula>
  </window>

  <window title="Import" name="importWindow" width="300">
    <tabs centered="true" small="true">
      <tab title="Vandmålere" padding="10">
        <upload name="metersUpload" url="data/ImportMeters.php" widget="meterImportButton">
          <placeholder title="Upload af CSV-fil med vandmålere" text="Filen skal have formatet (nummer;vej;postnummer;by) f.eks. (6778888;Toldbodvej 1;9370;Hals) eller (nummer;adresse) f.eks. (6778888;Toldbodvej 1,9370 Hals) og kan højest være '.$maxUploadSize.' stor"/>
        </upload>
        <buttons align="center" top="10">
          <button name="meterImportButton" text="Vælg filer..." highlighted="true"/>
        </buttons>
      </tab>
      <tab title="Aflæsninger" padding="10">
        <upload name="usagesUpload" url="data/ImportUsages.php" widget="usagesImportButton">
          <placeholder title="Upload af CSV-fil med aflæsninger" text="Filen skal have formatet (nummer;værdi;dato) f.eks. (6778888;21361;15/04/2011) og kan højest være '.$maxUploadSize.' stor"/>
        </upload>
        <buttons align="center" top="10">
          <button name="usagesImportButton" text="Vælg filer..." highlighted="true"/>
        </buttons>
      </tab>
    </tabs>
  </window>

  <window title="Aflæsning" icon="common/water" name="usageWindow" width="300" padding="5">
    <formula name="usageFormula">
      <fields labels="above">
        <field label="Nummer">
          <text-input key="number"/>
        </field>
        <field label="Værdi">
          <number-input key="value" max="1000000000"/>
        </field>
        <field label="Tidspunkt">
          <datetime-input key="date" return-type="seconds"/>
        </field>
      </fields>
      <buttons>
        <button name="cancelUsage" text="Annuller"/>
        <button name="deleteUsage" text="Slet">
          <confirm text="Er du sikker?" ok="Ja, slet" cancel="Nej"/>
        </button>
        <button name="saveUsage" text="Gem" highlighted="true"/>
      </buttons>
    </formula>
  </window>

  <window title="Vandmåler" icon="common/gauge" name="meterWindow" width="300" padding="5">
    <formula name="meterFormula">
      <fields labels="above">
        <field label="Nummer">
          <text-input key="number"/>
        </field>
      </fields>
      <buttons>
        <button name="cancelMeter" text="Annuller"/>
        <button name="createMeter" submit="true" text="Opret" highlighted="true"/>
      </buttons>
    </formula>
  </window>

  <boundpanel name="exportPanel" target="exportIcon" width="200" padding="5">
    <text align="center">
      <h>Eksport</h>
      <p>Her kan du eksportere alle aflæsninger i CSV-formatet:</p><p>"Number","Date","Value","Updated"</p>
    </text>
    <buttons align="center">
      <button click="exportPanel.hide()" text="Annuller"/>
      <button name="export" text="Eksportér" highlighted="true"/>
    </buttons>
  </boundpanel>

  <boundpanel name="statusPanel" padding="5" variant="light">
    <buttons align="center">
      <button text="Annuller" name="cancelStatus"/>
      <button text="Afvis" name="rejectStatus"/>
      <button text="Godkend" name="acceptStatus"/>
    </buttons>
  </boundpanel>
</gui>';

UI::render($gui);
?>