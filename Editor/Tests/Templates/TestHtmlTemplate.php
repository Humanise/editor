<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Templates
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class TestHtmlTemplate extends UnitTestCase {

  function testIt() {
    $page = TestService::createTestPage(['template' => 'html']);

    $this->assertNull(HtmlTemplate::load(-1));

    $obj = HtmlTemplate::load($page->getId());
    $this->assertNotNull($obj);
    $obj->setTitle('My blog');
    $obj->setHtml('<p>hello</p>');
    $obj->save();

    $ctrl = new HtmlTemplateController();

    $data = $ctrl->build($page->getId());
    $expected = '<html xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/html/1.0/"><title>My blog</title><content valid="true"><p>hello</p></content></html>';
    $this->assertEqual($expected,$data['data']);

    HtmlTemplateController::convertToDocument($page->getId());

    $page = Page::load($page->getId());

    TestService::removeTestPage($page);
  }
}