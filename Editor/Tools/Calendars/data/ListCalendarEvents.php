<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Calendars
 */
require_once '../../../Include/Private.php';

$calendarId = Request::getInt('calendarId');
if ($calendarId>0) {
  listEvents($calendarId,$force);
}

function listEvents($id,$force) {

  $query = ['calendarId'=>$id];
  $events = Event::search($query);

  $writer = new ListWriter();

  $writer->startList();
  //$writer->sort($sort,$direction);
  //$writer->window(array( 'total' => $list['total'], 'size' => $windowSize, 'page' => $windowPage ));
  $writer->startHeaders();
  $writer->header(['title'=>['Title', 'da'=>'Titel'], 'width'=>40]);
  $writer->header(['title'=>['Location', 'da'=>'Lokation']]);
  $writer->header(['title'=>'Start']);
  $writer->header(['title'=>['End', 'da'=>'Slut']]);
  $writer->endHeaders();

  foreach ($events as $event) {
    $writer->startRow(['kind'=>'event', 'id'=>$event->getId()]);
    $writer->startCell()->text($event->getTitle())->endCell();
    $writer->startCell()->text($event->getLocation())->endCell();
    $writer->startCell()->text(Dates::formatLongDateTime($event->getStartdate()))->endCell();
    $writer->startCell()->text(Dates::formatLongDateTime($event->getEnddate()))->endCell();
    $writer->endRow();
  }
  $writer->endList();
}