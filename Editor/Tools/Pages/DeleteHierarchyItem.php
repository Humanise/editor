<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/InternalSession.php';
require_once '../../Classes/Hierarchy.php';
require_once '../../Classes/Request.php';

$id = Request::getInt('id',0);

$hierarchyId = Hierarchy::deleteItem($id);

InternalSession::setToolSessionVar('pages','updateHier',true);

if (Request::exists('return')) {
    redirect(Request::getString('return'));
} else {
    redirect('EditHierarchy.php?id='.$hierarchyId);    
}
?>