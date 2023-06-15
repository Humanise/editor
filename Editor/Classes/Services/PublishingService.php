<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class PublishingService {

  static function publishPage($id) {

    $result = PublishingService::buildPage($id);
    if (!$result) {
      return;
    }
    $query = [
      'table' => 'page',
      'values' => [
        'data' => ['text' => $result['data']],
        'index' => ['text' => $result['index']],
        'published' => ['datetime' => time()],
        'dynamic' => ['boolean' => $result['dynamic']]
      ],
      'where' => [ 'id' => ['int' => $id] ]
    ];
    Database::update($query);

    PageService::createPageHistory($id, $result['data']);

    EventService::fireEvent('publish','page',null,$id);
  }

  static function reIndexPage($id) {

    $result = PublishingService::buildPage($id);
    if (!$result) {
      return;
    }

    $page = Page::load($id);
    $page->setIndex($result['index']);
    $page->save();
  }

  static function buildPage($id) {
    $sql = "select template.unique from page, template where page.template_id = template.id and page.id = @id";
    if ($row = Database::selectFirst($sql, $id)) {
      if ($controller = TemplateService::getController($row['unique'])) {
        if (method_exists($controller,'build')) {
          $result = $controller->build($id);
          return $result;
        }
      }
    }
    return null;
  }

  static function publishAll() {

    $pages = PublishingService::getUnpublishedPages();
    foreach ($pages as $page) {
      PublishingService::publishPage($page['id']);
    }

    $hierarchies = PublishingService::getUnpublishedHierarchies();
    foreach ($hierarchies as $hierarchy) {
      $obj = Hierarchy::load($hierarchy['id']);
      if ($obj) {
        $obj->publish();
      }
    }

    $objects = PublishingService::getUnpublishedObjects();
    foreach ($objects as $object) {
      if ($object) {
        $object->publish();
      }
    }

  }

  static function getTotalUnpublishedCount() {
    $count = 0;
    $sql = "select count(page.id) as `count`,'page' from page where page.changed>page.published
      union
      select count(hierarchy.id) as `count`,'hierarchy' from hierarchy where changed>published
      union
      select count(object.id) as `count`,'object' from object where updated>published";
    $rows = Database::selectAll($sql);
    foreach ($rows as $row) {
      $count += intval($row['count']);
    }
    return $count;
  }

  static function getUnpublishedPages() {
    $sql = "select page.id,page.title,template.unique as template from page,template where page.template_id=template.id and changed>published";
    return Database::selectAll($sql);
  }

  static function getUnpublishedHierarchies() {
    $sql = "select id,name from hierarchy where changed>published";
    return Database::selectAll($sql);
  }

  static function getUnpublishedObjects() {
    $result = [];
    $sql = "select id from object where updated>published";
    $ids = Database::getIds($sql);
    $notFound = [];
    foreach ($ids as $id) {
      if ($object = ModelObject::load($id)) {
        $result[] = $object;
      } else {
        $notFound[] = $id;
        Log::debug('Unable to load object: ' . $id);
      }

    }
    if ($notFound) {
      Log::debug('Not found:' . join(',', $notFound));
    }
    return $result;
  }

  static function publishFrame($id) {
    $sql = "select * from frame where id = @id";
    $row = Database::selectFirst($sql, $id);
    if (!$row) {
      return;
    }
    $data = '';
    if ($row['searchenabled']) {
      $data .= '<search page="' . $row['searchpage_id'] . '">' .
      '<button title="' . Strings::escapeEncodedXML($row['searchbuttontitle']) . '"/>' .
      '<types>' .
      ($row['searchpages'] ? '<type unique="page"/>' : '') .
      ($row['searchimages'] ? '<type unique="image"/>' : '') .
      ($row['searchfiles'] ? '<type unique="file"/>' : '') .
      ($row['searchnews'] ? '<type unique="news"/>' : '') .
      ($row['searchpersons'] ? '<type unique="person"/>' : '') .
      ($row['searchproducts'] ? '<type unique="product"/>' : '') .
      '</types>' .
      '</search>';
    }
    if ($row['userstatusenabled']) {
      $data .= '<userstatus page="' . $row['userstatuspage_id'] . '"/>';
    }
    $data .=
    '<text>' .
    '<bottom>' . Strings::insertEmailLinks(Strings::escapeEncodedXML($row['bottomtext']),'link','email','') . '</bottom>' .
    '</text>' .
    '<links>' .
    '<top>' .
    PublishingService::buildFrameLinks($id,'top') .
    '</top>' .
    '<bottom>' .
    PublishingService::buildFrameLinks($id,'bottom') .
    '</bottom>' .
    '</links>';
    $sql = "update frame set data = @text(data), published = now() where id = @id";
    Database::update($sql, ['data' => $data, 'id' => $id]);

    EventService::fireEvent('publish','frame',null,$id);
  }

  static function buildFrameLinks($id, $position) {
    $out = '';
    $sql = "select * from frame_link where position = @int(position) and frame_id = @id order by `index`";
    $result = Database::select($sql, ['position' => $position, 'id' => $id ]);
    while ($row = Database::next($result)) {
      $out .= '<link title="' . Strings::escapeEncodedXML($row['title']) . '" alternative="' . Strings::escapeEncodedXML($row['alternative']) . '"';
      if ($row['target_type'] == 'page') {
        $out .= ' page="' . $row['target_id'] . '"';
      }
      else if ($row['target_type'] == 'file') {
        $out .= ' file="' . $row['target_id'] . '" filename="' . Strings::escapeEncodedXML(FileService::getFileFilename($row['target_id'])) . '"';
      }
      else if ($row['target_type'] == 'url') {
        $out .= ' url="' . Strings::escapeEncodedXML($row['target_value']) . '"';
      }
      else if ($row['target_type'] == 'email') {
        $out .= ' email="' . Strings::escapeEncodedXML($row['target_value']) . '"';
      }
      $out .= '/>';
    }
    Database::free($result);
    return $out;
  }
}