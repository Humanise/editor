<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Model
 */
require_once '../../Include/Private.php';

$type = Request::getString('type');
$text = Request::getString('query');

$writer = new ItemsWriter();

$writer->startItems();
if (Request::getBoolean('includeEmpty')) {
    $writer->item([
      'title' => '',
      'value' => ''
    ]);
}
if ($type=='page') {
  $sql = "select page.id,page.title from page order by page.title";
  $result = Database::select($sql);
  while ($row = Database::next($result)) {
    $writer->item([
      'title' => $row['title'],
      'value' => $row['id']
    ]);
  }
  Database::free($result);
} else if ($type=='template') {
  $templates = TemplateService::getTemplatesSorted();
  foreach ($templates as $template) {
    $writer->item([
      'title' => $template['name'],
      'value' => $template['id']
    ]);
  }
} else if ($type=='frame') {
  $frames = Frame::search();
  foreach ($frames as $frame) {
    $writer->item([
      'title' => $frame->getName(),
      'value' => $frame->getId()
    ]);
  }
} else {
  $objects = Query::after($type)->withText($text)->orderBy('title')->withWindowSize(1000)->get();
  foreach ($objects as $object) {
    $writer->item([
      'title' => $object->getTitle(),
      'value' => $object->getId(),
      'icon' => $object->getIcon(),
      'kind' => $type
    ]);
  }
}
$writer->endItems();
?>