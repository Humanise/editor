<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Templates
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class WeblogTemplateController extends TemplateController {

  function WeblogTemplateController() {
    parent::TemplateController('weblog');
  }

  function create($page) {
    $sql = "insert into weblog (page_id) values (@int(id))";
    Database::insert($sql, ['id' => $page->getId()]);
    Log::debug($sql);
  }

  function delete($page) {
    $sql = "delete from weblog where page_id = @int(id)";
    Database::delete($sql, ['id' => $page->getId()]);
    $sql = "delete from weblog_webloggroup where page_id = @int(id)";
    Database::delete($sql, ['id' => $page->getId()]);
  }

  function build($id) {
    $sql = "select * from weblog where page_id = @int(id)";
    $row = Database::selectFirst($sql, ['id' => $id]);
    $data = '<weblog xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/weblog/1.0/">';
    $data .= '<title>' . Strings::escapeXML($row['title']) . '</title>';
    $data .= '<!--dynamic--></weblog>';
    return ['data' => $data, 'dynamic' => true, 'index' => ''];
  }

  function dynamic($id,&$state) {
    $xml = $this->_listEntries($id);
    $state['data'] = str_replace("<!--dynamic-->", $xml, $state['data']);
  }

  function _listEntries($id) {
    $xml = '';
    $sql = "select webloggroup_id as id from weblog_webloggroup where page_id = @int(id)";
    $selectedGroups = Database::getIds($sql, ['id' => $id]);

    $groups = Webloggroup::search(['page' => $id]);
    foreach ($groups as $group) {
      $xml .= '<group id="' . $group->getId() . '" title="' . Strings::escapeXML($group->getTitle()) . '" />';
    }
    $xml .= '<list>';

    $sql = "select distinct object.id,object.data as object_data,page.data as page_data,page.id as page_id,page.path from object,webloggroup_weblogentry,weblog_webloggroup,weblogentry left join page on weblogentry.page_id=page.id where weblog_webloggroup.page_id = @int(id) and weblog_webloggroup.webloggroup_id=webloggroup_weblogentry.webloggroup_id and webloggroup_weblogentry.weblogentry_id=weblogentry.object_id and object.id=weblogentry.object_id order by weblogentry.date desc";
    $result = Database::select($sql, ['id' => $id]);
    while ($row = Database::next($result)) {
      $xml .= '<entry';
      if ($row['path'] != '') {
        $xml .= ' page-path="' . $row['path'] . '"';
      } else if ($row['page_id'] != '') {
        $xml .= ' page-id="' . $row['page_id'] . '"';
      }
      $xml .= '>';
      $sql = "select object.title,object.id from webloggroup_weblogentry,object where webloggroup_weblogentry.webloggroup_id=object.id and weblogentry_id = @int(id)  order by object.title";
      $subResult = Database::select($sql, ['id' => $row['id']]);
      while ($subRow = Database::next($subResult)) {
        $xml .= '<group id="' . $subRow['id'] . '" title="' . Strings::escapeXML($subRow['title']) . '"/>';
      }
      Database::free($subResult);
      $xml .= $row['object_data'];
      $xml .= $row['page_data'];
      $xml .= '</entry>';
    }
    Database::free($result);

    $xml .= '</list>';
    return $xml;
  }

  function ajax($id) {
    $action = Request::getString('action');
    if ($action == 'createEntry') {
      $this->createEntry($id);
    } else if ($action == 'updateEntry') {
      $this->updateEntry();
    } else if ($action == 'deleteEntry') {
      $this->deleteEntry();
    } else if ($action == 'loadEntry') {
      $entryId = Request::getInt('entryId');
      if ($entry = Weblogentry::load($entryId)) {
        $entry->loadGroups();
        Response::sendObject($entry);
      } else {
        Response::badRequest();
      }
    }
  }

  function deleteEntry() {
    $entryId = Request::getInt('entryId');
    $entry = Weblogentry::load($entryId);
    if ($entry) {
      if ($page = Page::load($entry->getPageId())) {
        $page->delete();
      }
      $entry->remove();
    }
  }

  function createEntry($id) {
    $title = Request::getString('title');
    $text = Request::getString('text');
    $groups = Request::getIntArrayComma('groups');
    $date = Request::getInt('date');
    if ($title == '' && count($groups) == 0) {
      return false;
    }
    $entry = new Weblogentry();
    $entry->setTitle($title);
    $entry->setText($text);
    $entry->setDate($date);

    if ($blueprint = $this->getBlueprint($id)) {
      $page = new Page();
      $page->setTitle($title);
      $page->setTemplateId($blueprint->getTemplateId());
      $page->setDesignId($blueprint->getDesignId());
      $page->setFrameId($blueprint->getFrameId());
      $page->create();
      if ($page->getTemplateUnique() == 'html') {
        $sql = "update html set html = @text(html), title = @text(title), valid=0 where page_id = @int(pageId)";
        Database::update($sql, ['pageId' => $page->getId(), 'title' => $title, 'html' => $text]);
      }
      $page->publish();
      $entry->setPageId($page->getId());
    }
    $entry->create();
    $entry->publish();
    $entry->changeGroups($groups);
  }

  function getBlueprint($id) {
    $sql = "select pageblueprint_id from weblog where pageblueprint_id>0 and page_id = @int(id)";
    if ($row = Database::selectFirst($sql, ['id' => $id])) {
      return Pageblueprint::load($row['pageblueprint_id']);
    }
    return null;
  }

  function updateEntry() {
    $id = Request::getInt('entryId');
    $title = Request::getString('title');
    $text = Request::getString('text');
    $groups = Request::getIntArrayComma('groups');
    $date = Request::getInt('date');
    if ($title != '' && count($groups) > 0) {
      $entry = Weblogentry::load($id);
      $entry->setTitle($title);
      $entry->setText($text);
      $entry->setDate($date);
      $entry->save();
      $entry->changeGroups($groups);
      $entry->publish();

      if ($page = Page::load($entry->getPageId())) {
        if ($page->getTemplateUnique() == 'html') {
          $sql = "update html set html = @text(html), title = @text(title), valid=0 where page_id = @int(pageId)";
          Database::update($sql, ['pageId' => $page->getId(), 'title' => $title, 'html' => $text]);
          $page->publish();
        }
      }
    }
  }
}