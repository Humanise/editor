<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../Include/Private.php';

$simulate = Request::getBoolean('simulate');

$designItems = '';
$designOptions = '';
$designs = Query::after('design')->get();

if ($simulate) {
  $designs = [$designs[0]];
}

foreach ($designs as $design) {
  $designItems .= '<item text="' . Strings::escapeXML($design->getTitle()) . '" image="../../../style/' . $design->getUnique() . '/info/Preview128.png" value="' . $design->getId() . '"/>';
  $designOptions .= '<option text="' . Strings::escapeXML($design->getTitle()) . '" value="' . $design->getId() . '"/>';
}

$frameItems = '';
$frameOptions = '';
$frames = Frame::search();
foreach ($frames as $frame) {
  $frameItems .= '<option icon="common/settings" text="' . Strings::escapeXML($frame->getName()) . '" value="' . $frame->getId() . '"/>';
  $frameOptions .= '<option text="' . Strings::escapeXML($frame->getName()) . '" value="' . $frame->getId() . '"/>';
}

$templateItems = '';
$templates = TemplateService::getInstalledTemplates();
foreach ($templates as $template) {
  $info = TemplateService::getTemplateInfo($template['unique']);
  $templateItems .= '<item text="' . $info['name'] . '" image="../../Template/' . $template['unique'] . '/thumbnail128.jpg" value="' . $template['id'] . '"/>';
}

$languageItems = '
  <option value="" text="{None;da:Intet}"/>
  <option value="DA" text="{Danish;da:Dansk}"/>
  <option value="EN" text="{English;da:Engelsk}"/>
  <option value="DE" text="{German;da:Tysk}"/>
';

$gui = '
<gui xmlns="uri:hui" title="Sites" padding="10">
  <controller url="controller.js"/>
  <controller url="hierarchy.js"/>
  <controller url="advanced.js"/>
  <source name="pageListSource" url="data/ListPages.php">
    <parameter key="windowPage" value="@list.window.page"/>
    <parameter key="sort" value="@list.sort.key"/>
    <parameter key="direction" value="@list.sort.direction"/>
    <parameter key="query" value="@search.value"/>
    <parameter key="kind" value="@selector.kind"/>
    <parameter key="value" value="@selector.value"/>
    <parameter key="reviewSpan" value="@reviewSpan.value"/>
        <parameter key="advanced" value="@advanced.value"/>
  </source>
  <source name="pageFinderListSource" url="data/PageFinderList.php">
    <parameter key="windowPage" value="@pageFinderList.window.page"/>
    <parameter key="sort" value="@pageFinderList.sort.key"/>
    <parameter key="direction" value="@pageFinderList.sort.direction"/>
    <parameter key="text" value="@pageFinderSearch.value"/>
  </source>
  <source name="pagesSource" url="../../Services/Model/Items.php?type=page"/>
  <source name="filesSource" url="../../Services/Model/Items.php?type=file"/>
  <source name="languageSource" url="data/LanguageItems.php"/>
  <source name="selectionSource" url="data/NavigationItems.php"/>
  <source name="hierarchySource" url="data/HierarchyMenuItems.php"/>
  <source name="newPageHierarchySource" url="data/FrameHierarchyItems.php">
    <parameter key="frame" value="@frameSelection.value"/>
  </source>

  <structure>
    <top>
      <toolbar>
        <icon icon="common/page" text="{New page;da:Ny side}" overlay="new" name="newPage"/>
        <more>
          <icon icon="common/hierarchy_item" text="{New menu item;da:Nyt menupunkt}" overlay="new" name="newHierarchyItem" disabled="true"/>
          <icon icon="common/hierarchy" text="{New hierarchy;da:Nyt hierarki}" overlay="new" name="newHierarchy"/>
          <icon icon="common/settings" text="{Advanced...;da:Avanceret...}" name="advanced"/>
        </more>
        <divider/>
        <!--<icon icon="common/internet" overlay="upload" text="Udgiv ændringer" click="box.show()"/>-->
        <icon icon="common/edit" text="{Edit ; da:Rediger}" name="edit" disabled="true"/>
        <icon icon="common/info" text="Info" name="info" disabled="true"/>
        <icon icon="common/view" text="{Show ; da:Vis}" name="view" disabled="true"/>
        <icon icon="common/delete" text="{Delete;da:Slet}" name="delete" disabled="true">
          <confirm text="{Are you sure? It cannot be undone; da:Er du sikker? Det kan ikke fortrydes!}" ok="{Yes, delete; da:Ja, slet}" cancel="{Cancel;da:Annuller}"/>
        </icon>
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
            <options source="selectionSource"/>
          </selection>
        </overflow>
      </left>
      <center>
        <bar name="reviewBar" variant="layout" visible="false">
          <text text="{Shows a list of page reviews; da:Her vises en oversigt over revidering af sider}"/>
          <right>
          <segmented value="day" name="reviewSpan">
            <option text="{Show all; da:Vis alle}" value="all"/>
            <option text="{24 hours; da:Et døgn}" value="day"/>
            <option text="{7 days; da:7 dage}" value="week"/>
          </segmented>
          </right>
        </bar>
        <overflow>
          <list name="list" source="pageListSource" variant="light"/>
        </overflow>
      </center>
    </middle>
    <bottom>
      <space align="right" right="7" top="1">
        <checkbox text="{Advanced; da:Avanceret}" name="advanced"/>
      </space>
    </bottom>
  </structure>

  <window name="pageEditor" width="400" title="{Page ; da:Side}" icon="common/page">
    <toolbar>
      <icon icon="common/info" text="Info" selected="true" name="pageInfo"/>
      <!--
      <icon icon="common/settings" text="Avanceret" click="pageEditor.flip()"/>
      -->
      <icon icon="common/flag" text="{Language; da:Sprog}" name="pageTranslation"/>
      <right>
        <icon icon="common/internet" text="{Publish; da:Udgiv}" overlay="upload" name="publishPage" disabled="true"/>
        <icon icon="common/edit" text="{Edit ; da:Rediger}" name="editPage"/>
        <icon icon="common/view" text="{Show ; da:Vis}" name="viewPage"/>
      </right>
    </toolbar>
    <fragment name="pageInfoFragment">
      <formula name="pageFormula" padding="10">
        <fields labels="above">
          <field label="{Title ; da: Titel}:">
            <text-input key="title"/>
          </field>
          <field label="{Description; da:Beskrivelse}:">
            <text-input key="description" breaks="true"/>
          </field>
        </fields>
        <fields>
          <field label="{Language; da:Sprog}:">
            <dropdown key="language" placeholder="Vælg sprog...">
              ' . $languageItems . '
            </dropdown>
          </field>
          <field label="Design:">
            <dropdown key="designId">
              ' . $designOptions . '
            </dropdown>
          </field>
          <field label="{Setup; da:Opsætning}:">
            <dropdown key="frameId">
              ' . $frameOptions . '
            </dropdown>
          </field>
          <field label="{Path; da:Sti}:">
            <text-input key="path"/>
          </field>
          <field label="{Searchable; da:Søgbar}:">
            <checkbox key="searchable"/>
          </field>
          <field label="{Inactive; da:Inaktiv}:">
            <checkbox key="disabled"/>
          </field>
        </fields>
        <buttons>
          <button name="cancelPage" text="{Cancel; da:Annuller}"/>
          <button name="deletePage" text="{Delete; da:Slet}">
            <confirm text="{Are you sure? It cannot be undone! ; da:Er du sikker? Det kan ikke fortrydes!}" ok="{Yes, delete page; da:Ja, slet side}" cancel="{No; da:Nej}"/>
          </button>
          <button name="savePage" text="{Save; da:Gem}" highlighted="true"/>
        </buttons>
      </formula>
    </fragment>
    <fragment name="pageTranslationFragment" visible="false">
    <!--
      <bar>
        <button icon="common/new" text="Tilføj oversættelse" name="addTranslation"/>
      </bar>-->
      <list name="pageTranslationList" selectable="false"/>
      <buttons top="5" left="5" bottom="5">
        <button text="{Add translation; da:Tilføj oversættelse}" highlighted="true" small="true" name="addTranslation"/>
      </buttons>
    </fragment>
  </window>

  <window name="pageFinder" width="400" title="{Choose page; da:Vælg side}">
    <searchfield adaptive="true" name="pageFinderSearch"/>
    <overflow height="200">
      <list source="pageFinderListSource" name="pageFinderList"/>
    </overflow>
  </window>

  <window name="hierarchyEditor" width="300" title="{Hierarchy; da:Hierarki}" padding="10" icon="common/hierarchy">
    <formula name="hierarchyFormula">
      <fields>
        <field label="{Title; da:Titel}:">
          <text-input key="name"/>
        </field>
        <field label="{Language; da:Sprog}:">
          <dropdown key="language" placeholder="{Choose language; da:Vælg sprog...}">
            ' . $languageItems . '
          </dropdown>
        </field>
      </fields>
      <buttons>
        <button name="cancelHierarchy" text="{Cancel; da:Annuller}"/>
        <button name="deleteHierarchy" text="{Delete; da:Slet}">
          <confirm text="{Are your sure?; da:Er du sikker?}" ok="{Yes, delete hierarchy; da:Ja, slet hierarki}" cancel="{No; da:Nej}"/>
        </button>
        <button name="saveHierarchy" text="{Save; da:Gem}" highlighted="true" submit="true"/>
      </buttons>
    </formula>
  </window>

  <window name="hierarchyItemEditor" width="300" title="{Menu item; da:Menupunkt}" padding="10">
    <formula name="hierarchyItemFormula">
      <fields>
        <field label="{Title; da:Titel}:">
          <text-input key="title"/>
        </field>
        <field label="{Hidden; da:Skjult}:">
          <checkbox key="hidden"/>
        </field>
      </fields>
      <space height="10"/>
      <fieldset legend="Link">
        <fields>
          <field label="{Page; da:Side}:">
            <dropdown key="page" source="pagesSource" name="hierarchyItemPage"/>
          </field>
          <field label="{Referral; da:Reference}">
            <checkbox key="reference" name="hierarchyItemReference"/>
          </field>
          <field label="{File; da:Fil}:">
            <dropdown key="file" source="filesSource" name="hierarchyItemFile"/>
          </field>
          <field label="URL:">
            <text-input key="url" name="hierarchyItemURL"/>
          </field>
          <field label="{E-mail; da:E-post}:">
            <text-input key="email" name="hierarchyItemEmail"/>
          </field>
        </fields>
      </fieldset>
      <buttons top="10">
        <button name="cancelHierarchyItem" text="{Cancel; da:Annuller}"/>
        <button name="deleteHierarchyItem" text="{Delete; da:Slet}">
          <confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete item; da:Ja, slet punkt}" cancel="{Cancel; da:Annuller}"/>
        </button>
        <button name="saveHierarchyItem" text="{Save; da:Gem}" highlighted="true"/>
      </buttons>
    </formula>
  </window>

  <box absolute="true" name="newPageBox" padding="10" modal="true" width="636" variant="textured" title="{Creation of new page; da:Oprettelse af ny side}" closable="true">
    <wizard name="newPageWizard">
      <step title="{Type; da:Type}" icon="file/generic">
        <picker title="{Choose type; da:Vælg type}" name="templatePicker" shadow="true" item-height="128" item-width="96">
        ' . $templateItems . '
        </picker>
      </step>
      <step title="Design" key="design" icon="common/color">
        <picker title="{Choose design; da:Vælg design}" item-height="128" item-width="128" name="designPicker" shadow="true">
        ' . $designItems . '
        </picker>
      </step>
      <step title="{Setup; da:Grundopsætning}" icon="common/settings" padding="10" frame="true">
        <overflow max-height="200" min-height="160">
          <selection name="frameSelection">
            ' . $frameItems . '
          </selection>
        </overflow>
      </step>
      <step title="{Menu item; da:Menupunkt}" padding="10" frame="true" icon="common/hierarchy">
        <overflow height="160">
          <selection name="menuItemSelection">
            <options source="newPageHierarchySource"/>
          </selection>
        </overflow>
        <buttons top="10">
          <button name="noMenuItem" text="{No menu item; da:Intet menupunkt}" small="true"/>
        </buttons>
      </step>
      <step title="{Properties; da:Egenskaber}" padding="10" frame="true" icon="common/info">
        <overflow max-height="300" min-height="260">
        <formula name="newPageFormula">
          <fields labels="above">
            <field label="{Title; da:Titel}:">
              <text-input name="newPageTitle" key="title"/>
            </field>
            <field label="{Menu item; da:Menupunkt}:">
              <text-input key="menuItem"/>
            </field>
            <field label="{Path; da:Sti}:">
              <text-input key="path"/>
            </field>
            <field label="{Language; da:Sprog}:">
              <dropdown key="language" placeholder="{Choose language...; da:Vælg sprog...}">
                ' . $languageItems . '
              </dropdown>
            </field>
            <field label="{Description; da:Beskrivelse}:">
              <text-input breaks="true" key="description"/>
            </field>
          </fields>
        </formula>
        </overflow>
      </step>
    </wizard>
    <buttons top="10" align="right">
      <button text="{Previous; da:Forrige}" name="newPagePrevious"/>
      <button text="{Next; da:Næste}" name="newPageNext"/>
      <button text="{Cancel; da:Annuller}" name="newPageCancel"/>
      <button text="{Create page; da:Opret side}" name="createPage" highlighted="true"/>
    </buttons>
  </box>

  <box absolute="true" name="advancedBox" modal="true" width="636" variant="textured" title="{Advanced; da:Avanceret}" closable="true">
    <toolbar>
      <icon icon="common/page" text="{Special pages; da:Specielle sider}" selected="true" name="advancedSpecialPages"/>
      <icon icon="common/page" text="{Setups; da:Opsætninger}" name="advancedFrames"/>
      <icon icon="common/page" text="{Templates; da:Skabeloner}" name="advancedTemplates"/>
    </toolbar>
    <iframe height="400" name="advancedFrame"/>
  </box>

  <boundpanel name="previewer" width="300" variant="light" modal="true" padding="5">
    <iframe name="previewFrame" height="400"/>
  </boundpanel>
</gui>';
UI::render($gui);
?>