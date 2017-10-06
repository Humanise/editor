<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class NewsService {

  static function synchronizeSource($id,$force = false) {
    // TODO: Dont remove old items, only update existing
    $source = Newssource::load($id);
    if (!$source) return;
    if ($source->isInSync() && $force == false) {
      return;
    }
    NewsService::clearSource($id);
    $data = RemoteDataService::getRemoteData($source->getUrl(),30);
    if ($data->isHasData()) {
      $parser = new FeedParser();
      $feed = $parser->parseURL($data->getFile());
      if ($feed) {
        $items = $feed->getItems();
        foreach ($items as $item) {
          $srcItem = new Newssourceitem();
          $srcItem->setTitle(trim($item->getTitle()));
          $srcItem->setText(trim($item->getDescription()));
          $srcItem->setNewssourceId($source->getId());
          $srcItem->setGuid($item->getGuid());
          $srcItem->setDate($item->getPubDate());
          $srcItem->setUrl($item->getLink());
          $srcItem->save();
          $srcItem->publish();
        }
      }
    }
    $sql = 'update newssource set synchronized=now() where object_id=' . Database::int($id);
    Database::update($sql);
  }

  static function clearSource($id) {
    $items = Query::after('newssourceitem')->withProperty('newssource_id',$id)->get();
    foreach ($items as $item) {
      $item->remove();
    }
  }

  static function createArticle($article) {
    $blueprint = Pageblueprint::load($article->getPageBlueprintId());
    if (!$blueprint) {
      Log::debug('Unable to load blueprint with id=' . $article->getPageBlueprintId());
      return;
    }
    $page = new Page();
    $page->setTemplateId($blueprint->getTemplateId());
    $page->setDesignId($blueprint->getDesignId());
    $page->setFrameId($blueprint->getFrameId());
    $page->setTitle($article->getTitle());
    $page->save();

    $news = new News();
    $news->setTitle($article->getTitle());
    $news->setNote($article->getSummary());
    $news->setStartdate($article->getStartdate());
    $news->setEnddate($article->getEnddate());
    $news->save();
    $news->updateGroupIds($article->getGroupIds());

    $header = new HeaderPart();
    $header->setLevel(1);
    $header->setText($article->getTitle());
    $header->save();
    DocumentTemplateEditor::addPartAtEnd($page->getId(),$header);

    $text = new TextPart();
    $text->setText($article->getText());
    $text->save();
    DocumentTemplateEditor::addPartAtEnd($page->getId(),$text);

    $page->publish();

    ObjectLinkService::addPageLink($news,$page,$article->getLinkText());

    return ['page' => $page, 'news' => $news];
  }

  static function buildFeed($groupId) {
    $feed = new Feed();
    $feed->setTitle('Nyheder');
    $feed->setDescription('Nyheder');
    $feed->setPubDate(time());
    $feed->setLastBuildDate(time());
    $feed->setLink(ConfigurationService::getBaseUrl());


    $sql = "select object.id,object.title,object.note,UNIX_TIMESTAMP(news.startdate) as startdate,object_link.target_type,object_link.target_value from news,newsgroup_news,object left join object_link on object.id = object_link.object_id where object.id=news.object_id and newsgroup_news.news_id = news.object_id and newsgroup_news.newsgroup_id=@int(group) order by startdate desc,id,object_link.position";
    $result = Database::select($sql, ['group' => $groupId]);
    $ids[] = [];
    while ($row = Database::next($result)) {
      if (!in_array($row['id'],$ids)) {
        $item = new FeedItem();
        $item->setTitle($row['title']);
        $item->setDescription($row['note']);
        if ($row['startdate']) {
          $item->setPubDate($row['startdate']);
        }
        $link = null;
        if ($row['target_type'] == 'page') {
          $link = ConfigurationService::getBaseUrl() . '?id=' . $row['target_value'];
        } else if ($row['target_type'] == 'file') {
          $link = ConfigurationService::getBaseUrl() . '?file=' . $row['target_value'];
        } else if ($row['target_type'] == 'url' || $row['target_type'] == 'email') {
          $link = $row['target_value'];
        }
        if ($link) {
          $item->setGuid($link);
          $item->setLink($link);
        } else {
          $item->setGuid(ConfigurationService::getBaseUrl() . $row['id']);
        }
        $feed->addItem($item);
        $ids[] = $row['id'];
      }
    }
    Database::free($result);
    return $feed;
  }
}