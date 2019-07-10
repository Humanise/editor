<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Include/Private.php';

$main = Request::getString('main');
$group = Request::getInt('group');
$type = Request::getString('type');
$queryString = Request::getString('query');
$windowSize = Request::getInt('windowSize',30);
$windowPage = Request::getInt('windowPage',0);
$sort = Request::getString('sort');
$direction = Request::getString('direction');
if (!$sort) {
  $sort = 'title';
}
if (!$direction) {
  $direction = 'ascending';
}

InternalSession::setToolSessionVar('files','group',$group);

$q = Query::after('File')->orderBy($sort)->withDirection($direction)->withWindowPage($windowPage)->withText($queryString);

if ($group > 0) {
  $q->withCustom('group', $group);
}
if ($type) {
  $q->withCustom('mimetype', FileService::kindToMimeTypes($type));
}
if ($main == 'latest') {
  $q->withCreatedMin(Dates::addDays(time(),-1));
}

$result = $q->search();
$objects = $result->getList();

$writer = new ListWriter();

$writer->startList();
$writer->sort($sort,$direction);
$writer->window([ 'total' => $result->getTotal(), 'size' => $windowSize, 'page' => $windowPage ]);
$writer->startHeaders();
$writer->header(['title' => ['Title', 'da' => 'Titel'], 'width' => 40, 'sortable' => true, 'key' => 'title']);
$writer->header(['title' => 'Type']);
$writer->header(['title' => ['Size', 'da' => 'Størrelse'], 'sortable' => true, 'key' => 'file.size']);
$writer->header(['title' => ['Modified', 'da' => 'Ændret']]);
$writer->endHeaders();

foreach ($objects as $object) {
  $writer->
  startRow(['kind' => 'file', 'id' => $object->getId(), 'icon' => $object->getIcon(), 'title' => $object->getTitle()])->
    startCell(['icon' => $object->getIcon()])->
      startLine()->startWrap()->text($object->getTitle())->endWrap()->endLine()->
    endCell()->
    startCell()->
      startLine(['dimmed' => true])->text(FileService::mimeTypeToLabel($object->getMimeType()))->endLine()->
      //startLine(array('dimmed'=>true))->text($object->getFilename())->endLine()->
    endCell()->
    startCell()->startLine(['dimmed' => true])->text(UI::formatBytes($object->getSize()))->endLine()->endCell()->
    startCell()->startLine(['dimmed' => true])->text(Dates::formatDateTime($object->getUpdated()))->endLine()->endCell()->
  endRow();
}
$writer->endList();
?>