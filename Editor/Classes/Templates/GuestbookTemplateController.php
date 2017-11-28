<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Templates
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class GuestbookTemplateController extends TemplateController
{
  function GuestbookTemplateController() {
    parent::TemplateController('guestbook');
  }

  function create($page) {
    $sql = "insert into guestbook (page_id,title) values (@int(pageId), @text(title))";
    Database::insert($sql, ['pageId' => $page->getId(), 'title' => $page->getTitle()]);
  }

  function delete($page) {
    $sql = "delete from guestbook where page_id = @id";
    Database::delete($sql, $page->getId());
    $sql = "delete from guestbook_item where page_id = @id";
    Database::delete($sql, $page->getId());
  }

    function build($id) {
    $data = '<guestbook xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/guestbook/1.0/">';
    $data .= '<lang xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/internationalization/">';
    $data .= '<text key="list-header-time">Tid</text>';
    $data .= '<text key="list-header-name">Navn</text>';
    $data .= '<text key="list-header-text">Besked</text>';
    $data .= '<text key="action-new">Ny besked</text>';
    $data .= '<text key="action-cancel">Annuller</text>';
    $data .= '<text key="action-create">Opret</text>';
    $data .= '<text key="newitem-label-name">Navn</text>';
    $data .= '<text key="newitem-label-text">Besked</text>';
    $data .= '</lang>';
    $sql = "select title,text from guestbook where page_id = @id";
    $row = Database::selectFirst($sql, $id);
    $data .= '<title>' . Strings::escapeXML($row['title']) . '</title>';
    $data .= '<text>' . Strings::escapeSimpleXMLwithLineBreak($row['text'],'<break/>') . '</text>';
    $data .= '<!--dynamic-->';
    $data .= '</guestbook>';
    $index = $row['title'] . ' ' . $row['text'];
        return ['data' => $data, 'dynamic' => true, 'index' => $index];
    }

  function dynamic($id,&$state) {
    if (Request::getBoolean('newitem')) {
      $xml = "<newitem/>";
    }
    else {
      if (Request::isPost() && Request::getBoolean('userinteraction')) {
        $name = Request::getString('name');
        $text = Request::getString('text');
        $sql = "insert into guestbook_item (page_id,time,name,text) values (@int(pageId), now(), @text(name), @text(text))";
        Database::insert($sql, ['id' => $id, 'name' => $name, 'text' => $text]);
      }
      $sql = "select *,UNIX_TIMESTAMP(time) as unix from guestbook_item where page_id = @id order by time desc";
      $result = Database::select($sql, $id);
      $xml = '<list>';
      while ($row = Database::next($result)) {
        $xml .= '<item id="' . $row['id'] . '">';
        $xml .= RenderingService::buildDateTag('time',$row['unix']);
        $xml .= '<name>' . Strings::escapeXML($row['name']) . '</name>';
        $xml .= '<text>' . Strings::escapeSimpleXMLwithLineBreak($row['text'],'<break/>') . '</text>';
        $xml .= '</item>';
      }
      $xml .= '</list>';
      Database::free($result);
    }
    $state['data'] = str_replace("<!--dynamic-->", $xml, $state['data']);
  }

}