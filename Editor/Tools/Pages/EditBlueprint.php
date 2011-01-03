<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Pageblueprint.php';
require_once '../../Classes/Design.php';
require_once '../../Classes/Template.php';
require_once '../../Classes/Frame.php';

$id = Request::getInt('id');

$blueprint = PageBlueprint::load($id);
$designs = Design::search();
$frames = Frame::search();
$templates = Template::search();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="400" align="center" top="30">'.
'<titlebar title="'.In2iGui::escape($blueprint->getTitle()).'" icon="Element/Template">'.
'<close link="Blueprints.php"/>'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="SaveBlueprint.php" method="post" name="Formula" focus="title">'.
'<hidden name="id">'.$id.'</hidden>'.
'<group size="Large">'.
'<textfield badge="Titel:" name="title">'.In2iGui::escape($blueprint->getTitle()).'</textfield>'.
'<select badge="Skabelon:" name="template" selected="'.$blueprint->getTemplateId().'">';
foreach ($templates as $template) {
    $gui.='<option value="'.$template->getId().'" title="'.In2iGui::escape($template->getName()).'"/>';
}
$gui.=
'</select>'.
'<select badge="Design:" name="design" selected="'.$blueprint->getDesignId().'">';
foreach ($designs as $design) {
    $gui.='<option value="'.$design->getId().'" title="'.In2iGui::escape($design->getTitle()).'"/>';
}
$gui.=
'</select>'.
'<select badge="Ops�tning:" name="frame" selected="'.$blueprint->getFrameId().'">';
foreach ($frames as $frame) {
    $gui.='<option value="'.$frame->getId().'" title="'.In2iGui::escape($frame->getTitle()).'"/>';
}
$gui.=
'</select>'.
'<buttongroup size="Large">'.
'<button title="Slet" link="DeleteBlueprint.php?id='.$id.'"/>'.
'<button title="Annuller" link="Blueprints.php"/>'.
'<button title="Opdater" submit="true" style="Hilited"/>'.
'</buttongroup>'.
'</group>'.
'</form>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Form");
In2iGui::display($elements,$gui);
?>