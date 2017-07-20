<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.FrontPage
 */
require_once '../../Include/Private.php';

// Get variables
$partId = Request::getInt('partId');
$partType = Request::getString('partType');
$sectionId = Request::getInt('sectionId');

if (!$partType) {
  echo 'No type';
  exit;
}

$part = PartService::getController($partType);

$gui='
<gui xmlns="uri:hui" title="Toolbar">
  <controller url="js/PartToolbar.js"/>
  <controller url="../../Parts/'.$partType.'/toolbar.js"/>
  <script>
  partToolbar.pageId='.InternalSession::getPageId().';
  partToolbar.sectionId='.$sectionId.';
  partToolbar.partId='.$partId.';
  </script>
  <tabs small="true" below="true">';
    if ($part!=null && is_array($part->getToolbars())) {
      foreach ($part->getToolbars() as $title => $body) {
        $gui.='<tab title="'.$title.'" background="light">
        <toolbar>
          <icon icon="common/stop" text="{Cancel; da:Annuller}" click="partToolbar.cancel()"/>
          <icon icon="common/ok" text="{Save; da:Gem}" click="partToolbar.save()"/>
          <icon icon="common/delete" text="{Delete; da:Slet}" click="partToolbar.deletePart()">
            <confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete; da:Ja, slet}" cancel="{Cancel; da:Annuller}"/>
          </icon>
          <divider/>'.$body.
        '</toolbar>
        </tab>';
      }
    }
    $gui.='
    <tab title="{Positioning; da:Afstande}" background="light">
      <toolbar>
        <icon icon="common/stop" text="{Cancel; da:Annuller}" click="partToolbar.cancel()"/>
        <icon icon="common/ok" text="{Save; da:Gem}" click="partToolbar.save()"/>
        <icon icon="common/delete" text="{Delete; da:Slet}" click="partToolbar.deletePart()">
            <confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete; da:Ja, slet}" cancel="{Cancel; da:Annuller}"/>
        </icon>
        <divider/>
        <item label="{Left; da:Venstre}">
          <style-length-input name="marginLeft" width="90"/>
        </item>
        <item label="{Right; da:Højre}">
          <style-length-input name="marginRight" width="90"/>
        </item>
        <item label="{Top; da:Top}">
          <style-length-input name="marginTop" width="90"/>
        </item>
        <item label="{Bottom; da:Bund}">
          <style-length-input name="marginBottom" width="90"/>
        </item>
        <divider/>
        <item label="{Width; da:Bredde}">
          <style-length-input name="sectionWidth" width="90"/>
        </item>
        <item label="{Wrapping; da:Tekstomløb}">
          <segmented name="sectionFloat" allow-null="true">
            <option icon="style/float_none" value=""/>
            <option icon="style/float_left" value="left"/>
            <option icon="style/float_right" value="right"/>
          </segmented>
        </item>
        <item label="{Class; da:Klasse}">
          <text-input name="sectionClass" width="90"/>
        </item>
      </toolbar>
    </tab>
  </tabs>
</gui>';
UI::render($gui);
?>