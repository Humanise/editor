<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Templates
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class TestWeblogTemplate extends UnitTestCase {

  function testIt() {
    $page = TestService::createTestPage(['template' => 'weblog']);

    $this->assertNull(WeblogTemplate::load(-1));

    $weblog = WeblogTemplate::load($page->getId());
    $this->assertNotNull($weblog);
    $weblog->setTitle('My blog');
    $weblog->save();

    $ctrl = new WeblogTemplateController();

    $data = $ctrl->build($page->getId());
    $expected = '<weblog xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/weblog/1.0/"><title>My blog</title><!--dynamic--></weblog>';
    $this->assertEqual($expected,$data['data']);


    TestService::removeTestPage($page);
  }
}