<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.PageHistory
 */
require_once '../../../Include/Private.php';

$pageId = Request::getInt('pageId');

$writer = new ListWriter();

$writer->startList()->
  startHeaders()->
    header(['title'=>['User', 'da'=>'Bruger'], 'width'=>20])->
    header(['title'=>['Time', 'da'=>'Tidspunkt'], 'width'=>20])->
    header(['title'=>['Description', 'da'=>'Beskrivelse']])->
    header(['width'=>1])->
  endHeaders();

$sql="select page_history.id,UNIX_TIMESTAMP(page_history.time) as time,page_history.message,object.title".
" from page_history left join object on object.id=page_history.user_id where page_id=".Database::int($pageId)." order by page_history.time desc";

$result = Database::select($sql);
while ($row = Database::next($result)) {
  $writer->startRow(['id'=>$row['id']])->
    startCell(['icon'=>'common/user'])->text($row['title'])->endCell()->
    startCell(['wrap'=>false])->text(Dates::formatLongDateTime($row['time']))->endCell()->
    startCell()->text($row['message'])->startIcons()->icon(['icon'=>'monochrome/edit', 'revealing'=>true, 'action'=>true, 'data'=>['action'=>'editMessage']])->endIcons()->endCell()->
    startCell()->button(['text'=>['View', 'da'=>'Vis']])->endCell()->
    endRow();
}
Database::free($result);

$writer->endList();
?>