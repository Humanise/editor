<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Preview
 */
require_once '../../Include/Private.php';

if (Request::exists('id')) {
  InternalSession::setPageId(Request::getInt('id'));
}
$edit = Request::getBoolean('edit');

$gui = '
<gui xmlns="uri:hui" title="OnlinePublisher editor">
  <controller url="controller.js"/>
  <controller url="../../Template/document/live/live_ui.js"/>
  <controller url="../../Parts/widget/live_ui.js"/>
  <rows>
  <row height="content">
    <tabs small="true" below="true">
      <tab title="{View changes; da:Vis ændringer}" background="light">
        <toolbar>
          <icon icon="common/close" text="{Close; da:Luk}" name="close"/>
          <divider/>
          <icon icon="common/edit" text="{Edit; da:Rediger}" name="edit"/>
          <icon icon="common/view" text="{View published; da:Vis udgivet}" name="view"/>
          <icon icon="common/info" text="Info" name="properties"/>
          <divider/>
          <icon icon="common/internet" overlay="upload" text="{Publish; da:Udgiv}" name="publish" disabled="true"/>
          <more>
            <icon icon="common/page" text="{New page;da:Ny side}" overlay="new" name="newPage"/>
          </more>
        </toolbar>
      </tab>
      <tab title="{Advanced; da:Avanceret}" background="light">
        <toolbar>
          <icon icon="common/time" text="{History; da:Historik}" name="viewHistory"/>
          <divider/>
          <icon icon="inset/stamp" text="{Revise; da:Revidér}" name="review"/>
          <icon icon="common/note" text="{New note; da:Ny note}" name="addNote" overlay="new_monochrome"/>
          <divider/>
          <icon icon="common/settings" text="Design" name="design"/>
          <icon icon="common/settings" text="iPhone SE" name="iPhoneSmall"/>
          <icon icon="common/settings" text="iPhone 8" name="iPhoneMedium"/>
          <icon icon="common/settings" text="iPhone 8+" name="iPhoneLarge"/>
          <icon icon="common/settings" text="iPad" name="iPad"/>
        </toolbar>
      </tab>
    </tabs>
  </row>
  <row>
    <media-simulator name="simulator" url="viewer/' . ($edit ? '#edit' : '') . '"/>
  </row>
  </rows>

  <boundpanel target="addNote" name="notePanel" width="200">
    <formula name="noteFormula">
      <fields labels="above">
        <field label="Note:">
          <text-input key="text" breaks="true"/>
        </field>
        <field label="Type">
          <radiobuttons value="improvement" key="kind">
            <option value="improvement" text="{Improvement; da:Forbedring}"/>
            <option value="error" text="{Error; da:Fejl}"/>
          </radiobuttons>
        </field>
      </fields>
      <buttons>
        <button text="{Cancel; da:Annuller}" name="cancelNote" small="true"/>
        <button text="{Create; da:Opret}" highlighted="true" submit="true" small="true"/>
      </buttons>
    </formula>
  </boundpanel>

  <boundpanel target="review" name="reviewPanel" width="300">
    <buttons align="center" bottom="10">
      <button text="{Cancel; da:Annuller}" click="reviewPanel.hide()"/>
      <button text="{Reject; da:Afvis}" name="reviewReject"/>
      <button text="{Accept; da:Godkend}" highlighted="true" name="reviewAccept"/>
    </buttons>
    <list name="reviewList"/>
  </boundpanel>

  <boundpanel target="newPage" name="newPagePanel" width="300">
    <formula name="newPageFormula">
      <fields labels="above">
        <field label="{Title; da:Titel}:">
          <text-input key="title" value="{New page; da:Ny side}"/>
        </field>
        <field label="{Placement; da:Placering}">
          <radiobuttons value="below" key="placement">
            <option value="below" text="{Below; da:Underpunkt}"/>
            <option value="before" text="{Before; da:Før}"/>
            <option value="after" text="{After; da:Efter}"/>
          </radiobuttons>
        </field>
      </fields>
      <buttons>
        <button text="{Cancel; da:Annuller}" name="cancelNewPage" small="true"/>
        <button text="{Create; da:Opret}" highlighted="true" submit="true" small="true"/>
      </buttons>
    </formula>
  </boundpanel>

  <window name="pageProperties" width="300" padding="10" title="Info" icon="common/info">
    <formula name="pageFormula">
      <fields labels="above">
        <field label="{Title; da:Titel}:">
          <text-input key="title"/>
        </field>
        <field label="{Description; da:Beskrivelse}:">
          <text-input key="description" breaks="true"/>
        </field>
        <field label="{Keywords; da:Nøgelord}:">
          <text-input key="keywords"/>
        </field>
        <field label="{Path; da:Sti}:">
          <text-input key="path"/>
        </field>
        <field label="{Language; da:Sprog}:">
          <dropdown key="language" url="../Model/LanguageItems.php"/>
        </field>
      </fields>
      <buttons>
        <button text="{More...; da:Mere...}" name="morePageInfo"/>
        <button text="{Cancel; da:Annuller}" name="cancelPageProperties"/>
        <button text="{Update; da:Opdater}" highlighted="true" submit="true"/>
      </buttons>
    </formula>
  </window>

</gui>';

UI::render($gui);
?>
