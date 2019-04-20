<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Templates
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class SitemapTemplateController extends TemplateController
{
  function __construct() {
    parent::__construct('sitemap');
  }

  function create($page) {
    $sql = "insert into sitemap (page_id,title) values (@id, @text(title))";
    Database::insert($sql, ['id' => $page->getId(), 'title' => $page->getTitle()]);
  }

  function delete($page) {
    $sql = "delete from sitemap where page_id = @id";
    Database::delete($sql, $page->getId());
    $sql = "delete from sitemap_group where page_id = @id";
    Database::delete($sql, $page->getId());
  }

    function build($id) {
    $data = '<sitemap xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/sitemap/1.0/">';
    $sql = "select * from sitemap where page_id = @id";
    if ($row = Database::selectFirst($sql, $this->id)) {
      if ($row['title'] != '') {
        $data .= '<title>' . Strings::escapeXML($row['title']) . '</title>';
      }
      if ($row['text'] != '') {
        $data .= '<text>' . Strings::escapeXMLBreak($row['text'],'<break/>') . '</text>';
      }
    }

    $sql = "select sitemap_group.title,hierarchy.data from sitemap_group left join hierarchy on sitemap_group.hierarchy_id=hierarchy.id where page_id = @id order by sitemap_group.position";
    $result = Database::select($sql, $this->id);
    while ($row = Database::next($result)) {
        $data .= '<group title="' . Strings::escapeXML($row['title']) . '">' .
        $row['data'] .
        '</group>';
    }
    Database::free($result);

    $data .= '</sitemap>';
        return ['data' => $data, 'dynamic' => false, 'index' => ''];
    }

}