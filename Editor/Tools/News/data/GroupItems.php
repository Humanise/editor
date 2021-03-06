<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Include/Private.php';

$writer = new ItemsWriter();

$writer->startItems();

$sql = "select distinct object.id,object.title,count(news.object_id) as newscount from newsgroup, newsgroup_news, news,object where newsgroup_news.newsgroup_id=newsgroup.object_id and newsgroup_news.news_id = news.object_id and object.id=newsgroup.object_id group by newsgroup.object_id union select object.id,object.title,'0' from object left join newsgroup_news on newsgroup_news.newsgroup_id=object.id where object.type='newsgroup' and newsgroup_news.news_id is null order by title";
$result = Database::select($sql);
while ($row = Database::next($result)) {
  $options = [
    'value' => $row['id'],
    'title' => $row['title'],
    'icon' => 'common/folder',
    'kind' => 'newsgroup'
  ];
  if ($row['newscount'] > 0) {
    $options['badge'] = $row['newscount'];
  }
  $writer->startItem($options)->endItem();
}
Database::free($result);

$writer->endItems();
?>


