<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../Include/Private.php';

$gui = '
<gui xmlns="uri:hui" title="Special pages">
  <source url="data/ListFrames.php" name="listSource"/>
  <controller url="frames.js"/>
  <source name="pageItems" url="../../Services/Model/Items.php?type=page"/>
  <source name="fileItems" url="../../Services/Model/Items.php?type=file"/>
  <source name="newsGroupItems" url="../../Services/Model/Items.php?type=newsgroup"/>
  <source name="hierarchyItems" url="data/HierarchyItems.php"/>
  <toolbar>
    <icon icon="common/page" overlay="new" text="{New setup; da:Ny opsætning}" name="newFrame"/>
  </toolbar>
  <overflow>
    <list name="list" source="listSource"/>
  </overflow>

  <window name="frameWindow" width="500" title="{Setup; da:Opsætning}">
    <tabs small="true" centered="true">
      <tab title="Info" padding="10">
        <form name="frameFormula">
          <fields>
            <field label="{Name; da:Navn}:">
              <text-input key="name"/>
            </field>
            <field label="{Title; da:Titel}:">
              <text-input key="title"/>
            </field>
            <field label="{Bottom text; da:Bund-tekst}:">
              <text-input key="bottomText" breaks="true"/>
            </field>
            <field label="{Hierarchy; da:Hierarki}:">
              <dropdown key="hierarchyId" source="hierarchyItems" placeholder="{Choose hierarchy; da:Vælg hierarki...}"/>
            </field>
          </fields>
        </form>
      </tab>
      <tab title="{Search; da:Søgning}" padding="10">
        <form name="searchFormula">
          <fields>
            <field label="{Active; da:Aktiv}:">
              <checkbox key="enabled"/>
            </field>
            <field label="{Search page; da:Søgeside}:">
              <dropdown key="pageId" source="pageItems" placeholder="{Choose search page...; da:Vælg søgeside...}"/>
            </field>
          </fields>
        </form>
      </tab>
      <tab title="{User; da:Bruger}" padding="10">
        <form name="userFormula">
          <fields>
            <field label="{Active; da:Aktiv}:">
              <checkbox key="enabled"/>
            </field>
            <field label="{Login page; da:Login-side}:">
              <dropdown key="pageId" source="pageItems" placeholder="{Choose login page...; da:Vælg login-side...}"/>
            </field>
          </fields>
        </form>
      </tab>
      <tab title="{Top links; da:Top-links}">
        <toolbar centered="true">
          <icon title="{New link; da:Nyt link}" icon="common/link" overlay="new" click="topLinks.addLink()"/>
        </toolbar>
        <links name="topLinks" pageSource="pageItems" fileSource="fileItems"/>
      </tab>
      <tab title="{Bottom links; da:Bund-links}">
        <toolbar centered="true">
          <icon title="{New link; da:Nyt link}" icon="common/link" overlay="new" click="bottomLinks.addLink()"/>
        </toolbar>
        <links name="bottomLinks" pageSource="pageItems" fileSource="fileItems"/>
      </tab>
    </tabs>
    <space all="10">
    <buttons align="right">
      <button name="cancelFrame" title="{Cancel; da:Annuller}"/>
      <button name="deleteFrame" title="{Delete; da:Slet}">
        <confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete; da:Ja, slet}" cancel="{No; da:Nej}"/>
      </button>
      <button name="saveFrame" title="{Save; da:Gem}" highlighted="true"/>
    </buttons>
    </space>
  </window>

</gui>';
UI::render($gui);
?>