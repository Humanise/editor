<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');
$type = Request::getString('type');

$sql = "select `id`,`left`,`right`,`top`,`bottom`,`float`,`width`,`style`,`class` from `document_section` where `part_id`=@int(id)";
$section = Database::selectFirst($sql, ['id' => $id]);
if ($section) {
  $section = [
    'id' => intval($section['id']),
    'left' => $section['left'],
    'right' => $section['right'],
    'top' => $section['top'],
    'bottom' => $section['bottom'],
    'float' => $section['float'],
    'width' => $section['width'],
    'style' => $section['style'],
    'class' => $section['class']
  ];
  $part = PartService::load($type,$id);
  //Response::sendObject($part);
  Response::sendObject([
    'part' => $part,
    'section' => $section
  ]);
}

?>