<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');
$image = Image::load($id);

$groups = $image->getGroupIds();

Response::sendUnicodeObject(array('image' => $image, 'groups' => $groups));
?>