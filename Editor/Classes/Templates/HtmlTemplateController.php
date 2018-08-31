<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Templates
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class HtmlTemplateController extends TemplateController
{
  function __construct() {
    parent::__construct('html');
  }

  function create($page) {
    $html = '<h1>' . Strings::escapeXML($page->getTitle()) . '</h1>';
    $sql = "insert into html (page_id,html,valid) values (@int(pageId), @text(html), 1)";
    Database::insert($sql, ['pageId' => $page->getId(), 'html' => $html]);
  }

  function delete($page) {
    $sql = "delete from html where page_id = @id";
    Database::delete($sql, $page->getId());
  }

  function build($id) {
    $data = '<html xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/html/1.0/">';
    $sql = "select html,valid,title from html where page_id = @id";
    if ($row = Database::selectFirst($sql, $id)) {
      if (strlen($row['title']) > 0) {
        $data .= '<title>' . Strings::escapeXML($row['title']) . '</title>';
      }
      if ($row['valid']) {
        $data .= '<content valid="true">' . $row['html'] . '</content>';
      } else {
        $data .= '<content valid="false"><![CDATA[' . $row['html'] . ']]></content>';
      }
    }
    $data .= '</html>';
    return ['data' => $data, 'dynamic' => false, 'index' => ''];
  }

  static function convertToDocument($id) {
    $template = TemplateService::getTemplateByUnique('document');
    if (!$template) {
      return;
    }
    $sql = "select html,title from html where page_id = @id";
    if ($row = Database::selectFirst($sql, $id)) {

      $page = Page::load($id);
      $ctrl = new DocumentTemplateController();
      $ctrl->create($page);

      $sql = "delete from html where page_id = @id";
      Database::delete($sql, $page->getId());

      $page->setTemplateId($template->getId());
      $page->save();

      $part = new HtmlPart();
      $part->setHtml($row['html']);
      $part->save();
      DocumentTemplateEditor::addPartAtEnd($page->getId(), $part);
      PageService::markChanged($id);
    }
  }

}