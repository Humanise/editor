<?php
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class PageService {

  static function createPageHistory($id, $data) {
    $sql = "insert into page_history (page_id,user_id,data,time) values (@int(pageId), @int(userId), @text(data) ,now())";
    Database::insert($sql, ['pageId' => $id, 'data' => $data, 'userId' => InternalSession::getUserId()]);
  }

  static function listHistory($pageId) {
    $sql = "select page_history.id,UNIX_TIMESTAMP(page_history.time) as time,page_history.message,object.title" .
    " from page_history left join object on object.id=page_history.user_id where page_id = @id order by page_history.time desc";
    return Database::selectAll($sql, $pageId);
  }

  static function updateHistoryMessage($id, $text) {
    $sql = "update page_history set message = @text(text) where id = @id";
    Database::update($sql, ['text' => $text, 'id' => $id]);
  }

  static function getHistoryMessage($id) {
    $sql = "select message from page_history where id = @id";
    if ($row = Database::selectFirst($sql, $id)) {
      return $row['message'];
    }
    return null;
  }

  static function exists($id) {
    return !Database::isEmpty("SELECT id from page where id = @id", $id);
  }

  static function getLatestPageId() {
    $sql = "SELECT id from page order by changed desc limit 1";
    $row = Database::selectFirst($sql);
    if ($row) {
      return intval($row['id']);
    }
    return null;
  }

  static function getLanguage($id) {
    $sql = "SELECT language from page where id = @id";
    if ($row = Database::selectFirst($sql, $id)) {
      return strtolower($row['language']);
    }
    return null;
  }

  static function getSimplePageInfo($id) {
    $sql = "select `template`.`unique` as template,`design`.`unique` as design from page,template,design where page.template_id = template.id and page.design_id=design.object_id and page.id = @id";
    return Database::selectFirst($sql, $id);
  }

  static function getPath($id) {
    $sql = "SELECT path from page where id = @int(id)";
    if ($row = Database::selectFirst($sql, $id)) {
      return trim($row['path']);
    }
    return null;
  }

  static function getTotalPageCount() {
    $sql = "SELECT count(id) as count from page";
    $row = Database::selectFirst($sql);
    return intval($row['count']);
  }

  static function getChangedPageCount() {
    $sql = "SELECT count(id) as count from page where page.changed > page.published";
    $row = Database::selectFirst($sql);
    return intval($row['count']);
  }

  static function getLatestPageCount() {
    $sql = "SELECT count(id) as count from page where page.changed > @datetime(time)";
    $row = Database::selectFirst($sql, ['time' => Dates::addDays(time(),-1)]);
    return intval($row['count']);
  }

  static function getNewsPageCount() {
    $sql = "SELECT count(page.id) as total from page,template,news, object_link where page.template_id=template.id  and object_link.object_id=news.object_id and object_link.target_value=page.ID and object_link.target_type='page'";
    $row = Database::selectFirst($sql);
    return intval($row['total']);
  }

  static function getWarningsPageCount() {
    $sql = "SELECT count(page.id) as total from page,template where page.template_id=template.id  and (page.changed>page.published or page.path is null or page.path='')";
    $row = Database::selectFirst($sql);
    return intval($row['total']);
  }

  static function getNoItemPageCount() {
    $sql = "SELECT count(page.id) as total from page,template where page.template_id=template.id  and page.id not in (select target_id from `hierarchy_item` where `target_type`='page')";
    $row = Database::selectFirst($sql);
    return intval($row['total']);
  }

  static function getReviewPageCount() {
    $sql = "SELECT count(page.id) as total
      from page,relation as page_review,relation as review_user,review,object as user
      where page_review.from_type='page' and page_review.from_object_id=page.id
      and page_review.to_type='object' and page_review.to_object_id=review.object_id
      and review_user.from_type='object' and review_user.from_object_id=review.object_id
      and review_user.to_type='object' and review_user.to_object_id=user.id
      and review.accepted = 0";
    $row = Database::selectFirst($sql);
    return intval($row['total']);
  }


  static function getLanguageCounts() {
    $sql = "SELECT language,count(id) as count from page group by language order by language";
    return Database::selectAll($sql);
  }

  static function getPagePreview($id,$template) {
    $data = '';
    if ($controller = TemplateService::getController($template)) {
      if (method_exists($controller,'build')) {
        $result = $controller->build($id);
            return $result['data'];
      }
    }
    return $data;
  }

  static function saveSnapshot($id) {
    $page = Page::load($id);
    if ($page) {
      $template = $page->getTemplateUnique();
      $data = PageService::getPagePreview($id,$template);
      PageService::createPageHistory($id,$data);
    }
  }

  static function getBlueprintsByTemplate($template) {
    $sql = "SELECT object_id as id from pageblueprint,template where pageblueprint.template_id = template.`id` and template.`unique` = @text(template)";
    $ids = Database::getIds($sql, ['template' => $template]);
    if (count($ids) > 0) {
      return Query::after('pageblueprint')->withIds($ids)->orderBy('title')->get();
    }
    return [];
  }

  static function getPageTranslationList($id) {
    $sql = "SELECT page_translation.id, page.title, page.language from page, page_translation where page.id = page_translation.translation_id and page_translation.page_id = @id order by page.title";
    return Database::selectAll($sql, $id);
  }

  static function addPageTranslation($page,$translation) {
    $params = ['page' => $page, 'translation' => $translation];
    $sql = "select id from page_translation where page_id = @int(page) and translation_id = @int(translation)";
    if (Database::isEmpty($sql, $params)) {
      $sql = "INSERT into page_translation (page_id,translation_id) values (@int(page), @int(translation))";
      Database::insert($sql, $params);
      PageService::markChanged($page);
    }
  }

  static function removePageTranslation($id) {
    $sql = "SELECT page_id from page_translation where id = @id";
    if ($row = Database::selectFirst($sql, $id)) {
      $sql = "delete from page_translation where id = @id";
      Database::delete($sql, $id);
      PageService::markChanged($row['page_id']);
      return true;
    }
    return false;
  }

  static function markChanged($id) {
    $sql = "UPDATE page set changed=now() where id = @id";
    Database::update($sql, $id);
  }

  static function isChanged($id) {
    $sql = "SELECT changed-published as delta from page where id = @id";
    if ($row = Database::selectFirst($sql, $id)) {
      if ($row['delta'] > 0) {
        return true;
      }
    }
    return false;
  }

  static function getIndex($pageId) {
    $sql = "SELECT `index` from page where id = @id";
    $row = Database::selectFirst($sql, $pageId);
    if ($row) {
      return $row['index'];
    }
    return null;
  }

  static function getLinkText($pageId) {
    $text = '';
    $sql = "SELECT text from part_text,document_section where document_section.part_id = part_text.part_id and page_id = @id
      union select text from part_header,document_section where document_section.part_id = part_header.part_id and page_id = @id
      union select text from part_listing,document_section where document_section.part_id = part_listing.part_id and page_id = @id
      union select html as text from part_table,document_section where document_section.part_id = part_table.part_id and page_id = @id";
    $result = Database::select($sql, $pageId);
    while ($row = Database::next($result)) {
      $text .= ' ' . $row['text'];
    }
    Database::free($result);
    return $text;
  }

  static function updateSecureStateOfAllPages() {
    $sql = "UPDATE page set secure = 1";
    Database::update($sql);
    $sql = "UPDATE page left join securityzone_page on page.id = securityzone_page.page_id set page.secure = 0 where securityzone_page.securityzone_id is null";
    Database::update($sql);
  }

  static function addPageToSecurityZone($pageId,$zoneId) {
    if (!Securityzone::load($zoneId)) {
      Log::warn('Zone not found: ' . $zoneId);
      return;
    }
    if (!PageService::exists($pageId)) {
      Log::warn('Page not found: ' . $pageId);
      return;
    }
    $parameters = ['pageId' => $pageId, 'zoneId' => $zoneId];
    $sql = "DELETE from securityzone_page where page_id = @int(pageId) and securityzone_id = @int(zoneId)";
    Database::delete($sql,$parameters);
    $sql = "INSERT into securityzone_page (page_id, securityzone_id) values (@int(pageId), @int(zoneId))";
    Database::insert($sql,$parameters);
    PageService::updateSecureStateOfAllPages();
  }

  static function removePageFromSecurityZone($pageId,$zoneId) {
    if (!Securityzone::load($zoneId)) {
      Log::warn('Zone not found: ' . $zoneId);
      return;
    }
    if (!PageService::exists($pageId)) {
      Log::warn('Page not found: ' . $pageId);
      return;
    }
    $parameters = ['pageId' => $pageId, 'zoneId' => $zoneId];
    $sql = "DELETE from securityzone_page where page_id = @int(pageId) and securityzone_id = @int(zoneId)";
    Database::delete($sql,$parameters);
    PageService::updateSecureStateOfAllPages();
  }

  static function addUserToSecurityZone($userId,$zoneId) {
    if (!User::load($userId)) {
      Log::warn('User not found: ' . $userId);
      return;
    }
    if (!Securityzone::load($zoneId)) {
      Log::warn('Zone not found: ' . $zoneId);
      return;
    }
    $parameters = ['userId' => $userId, 'zoneId' => $zoneId];
    $sql = "DELETE from securityzone_user where user_id = @int(userId) and securityzone_id = @int(zoneId)";
    Database::delete($sql,$parameters);
    $sql = "INSERT into securityzone_user (user_id, securityzone_id) values (@int(userId), @int(zoneId))";
    Database::insert($sql,$parameters);
  }

  static function removeUserFromSecurityZone($userId,$zoneId) {
    if (!User::load($userId)) {
      Log::warn('User not found: ' . $userId);
      return;
    }
    if (!Securityzone::load($zoneId)) {
      Log::warn('Zone not found: ' . $zoneId);
      return;
    }
    $sql = "DELETE from securityzone_user where user_id = @int(userId) and securityzone_id = @int(zoneId)";
    Database::delete($sql, ['userId' => $userId, 'zoneId' => $zoneId]);
  }

  static function search($query) {

    $select = new SelectBuilder();

    $select->addTable('page')->addTable('template');

    $select->addColumns([
      "page.id",
      "page.secure",
      "page.path",
      "page.title",
      "template.unique",
      "date_format(page.changed,'%d/%m-%Y') as changed",
      "date_format(page.changed,'%Y%m%d%h%i%s') as changedindex",
      "(page.changed-page.published) as publishdelta",
      "page.language"
    ]);

    $select->addLimit("page.template_id=template.id");

    // Free text search...
    $text = $query->getText();
    if (Strings::isNotBlank($text)) {
      $select->addLimit(
        "(page.title like " . Database::search($text) .
        " or page.`index` like " . Database::search($text) .
        " or page.description like " . Database::search($text) .
        " or page.keywords like " . Database::search($text) . ")"
      );
    }
    // Relations...
    if (count($query->getRelationsFrom()) > 0) {
      $relations = $query->getRelationsFrom();
      for ($i = 0; $i < count($relations); $i++) {
        $relation = $relations[$i];
        $select->addTable('relation as relation_from_' . $i);
        $select->addLimit('relation_from_' . $i . '.to_object_id=page.id');
        $select->addLimit("relation_from_" . $i . ".to_type='page'");
        $select->addLimit("relation_from_" . $i . ".from_type=" . Database::text($relation['fromType']));
        $select->addLimit('relation_from_' . $i . '.from_object_id=' . Database::int($relation['id']));
        if ($relation['kind']) {
          $select->addLimit("relation_from_" . $i . ".kind=" . Database::text($relation['kind']));
        }
      }
    }
    $ordering = $query->getOrdering();
    for ($i = 0; $i < count($ordering); $i++) {
      $select->addOrdering($ordering[$i],$query->getDirection() == 'descending');
    }

    $windowPage = $query->getWindowPage();
    $windowSize = $query->getWindowSize();

    $select->setFrom($windowPage * $windowSize)->setTo(($windowPage + 1) * $windowSize);

    $result = new SearchResult();

    $list = Database::selectAll($select->toSQL());
    $result->setList($list);

    $select->clearFromAndTo()->clearColumns();
    $select->addColumn("count(page.id) as total");

    $count = Database::selectFirst($select->toSQL());
    $result->setTotal(intval($count['total']));

    return $result;
  }

  static function validate($page) {
    if (!$page) {
      return false;
    }
    if (Strings::isBlank($page->getTitle())) {
      return false;
    }
    if (!$page->getTemplateId()) {
      return false;
    }
    $sql = "SELECT id from template where id = @id";
    if (Database::isEmpty($sql, $page->getTemplateId())) {
      return false;
    }
    $sql = "SELECT id from frame where id = @id";
    if (Database::isEmpty($sql, $page->getFrameId())) {
      return false;
    }
    $design = Design::load($page->getDesignId());
    if (!$design) {
      return false;
    }
    return true;
  }

  static function create($page) {
    if (!PageService::validate($page)) {
      return false;
    }
    $time = time();
    $page->setCreated($time);
    $page->setChanged($time);
    $page->setPublished($time);
    ModelService::save($page);

    PageService::_createTemplate($page);
    return true;
  }

  static function _createTemplate($page) {
    $template = Template::load($page->getTemplateId());
    $page->templateUnique = $template->getUnique();
    if ($controller = TemplateService::getController($page->getTemplateUnique())) {
      if (method_exists($controller,'create')) {
        $controller->create($page);
      }
    }
  }

  static function delete($page) {
    if ($controller = TemplateService::getController($page->getTemplateUnique())) {
      if (method_exists($controller,'delete')) {
        $controller->delete($page);
      }
    }

    $id = $page->getId();

    // Delete the page
    $sql = "delete from page where id = @id";
    Database::delete($sql, $id);

    // Delete links
    $sql = "delete from link where page_id = @id";
    Database::delete($sql, $id);

    // Delete translations
    $sql = "delete from page_translation where page_id = @id or translation_id = @id";
    Database::delete($sql, $id);

    // Delete history
    $sql = "delete from page_history where page_id = @id";
    Database::delete($sql, $id);

    // Delete security zone relations
    $sql = "delete from securityzone_page where page_id = @id";
    Database::delete($sql, $id);

    EventService::fireEvent('delete','page',$page->getTemplateUnique(),$id);
  }

  static function load($id) {
    return ModelService::load('Page', $id);
  }

  static function save($page) {
    $success = false;
    if ($page->getId() > 0) {
      $existing = PageService::load($page->getId());

      if ($existing && Strings::isNotBlank($existing->getPath())) {
        if ($page->getPath() !== $existing->getPath()) {
          Log::debug('The path has changed!');
          $path = Query::after('path')->withProperty('path',$existing->getPath())->first();
          if ($path) {
            Log::debug('Found existing path...');
            Log::debug($path);
            Log::debug('Point it at the new page...');
            $path->setPageId($page->getId());
          } else {
            Log::debug('Creating new path...');
            $path = new Path();
            $path->setPath($existing->getPath());
            $path->setPageId($page->getId());
          }
          $path->save();
          $path->publish();
        }
      }

      $success = ModelService::save($page);

      if ($success) {
        PageService::markChanged($page->getId());
        EventService::fireEvent('update','page',$page->getTemplateUnique(),$page->getId());
      }
    } else {
      $success = PageService::create($page);
    }
    if ($success) {
      if (Strings::isNotBlank($page->getPath())) {
        $paths = Query::after('path')->withProperty('path',$page->getPath())->get();
        foreach ($paths as $path) {
          $path->remove();
        }
      }
    }
  }


  static function reconstruct($pageId, $historyId) {
    $page = PageService::load($pageId);
    if (!$page) {
      Log::debug('Page not found: ' . $pageId);
    } else if ($controller = TemplateService::getController($page->getTemplateUnique())) {
      $sql = "select data from page_history where id = @id";
      if ($row = Database::selectFirst($sql, $historyId)) {
        if ($doc = DOMUtils::parse(Strings::toUnicode($row['data']))) {
          $controller->import($page->getId(),$doc);
          PageService::markChanged($page->getId());
          return true;
        } else {
          Log::debug('Unable to parse data: ' . Strings::shortenString($row['data'],100));
          Log::debug('Valid: ' . (DOMUtils::isValid($row['data']) ? 'true' : 'false'));

        }
      } else {
        Log::debug('History not found: ' . $historyId);
      }
    } else {
      Log::debug('No controller found for...');
      Log::debug($page);
    }
    return false;
  }

  /**
   * Creates a new page using the document template
   * @param $pageId The ID of the page to create the new page in relation to
   * @param $title The title of the page
   * @param $placement If the new page should be placed 'below', 'before' or 'after'
   */
  static function createPageContextually($pageId, $title, $placement) {
    if (!in_array($placement,['below', 'before', 'after'])) {
      Log::debug('Unsupported placement');
      return false;
    }
    $context_page = Page::load($pageId);
    if (!$context_page) {
      Log::debug('No page');
      return false;
    }
    $context_item = HierarchyItem::loadByPageId($context_page->getId());
    $template = TemplateService::getTemplateByUnique('document');
    if ($context_item && $template) {
      $page = new Page();
      $page->setTitle($title);
      $page->setTemplateId($template->getId());
      $page->setDesignId($context_page->getDesignId());
      $page->setFrameId($context_page->getFrameId());
      $page->setLanguage($context_page->getLanguage());
      if ($page->create()) {
        $hierarchy = Hierarchy::load($context_item->getHierarchyId());
        if (!$hierarchy) {
          Log::debug('No hierarchy');
          return false;
        }

        $recipe = [
          'title' => $title,
          'targetType' => 'page',
          'hidden' => false,
          'targetValue' => $page->getId()
        ];
        if ($placement == 'before') {
          $recipe['parent'] = $context_item->getParent();
          $recipe['index'] = $context_item->getIndex();
        } else if ($placement == 'after') {
          $recipe['parent'] = $context_item->getParent();
          $recipe['index'] = $context_item->getIndex() + 1;
        } else { // below
          $recipe['parent'] = $context_item->getId();
        }
        $success = $hierarchy->createItem($recipe); // TODO What if this fails
        return $page;
      }
    }
    return false;
  }
}