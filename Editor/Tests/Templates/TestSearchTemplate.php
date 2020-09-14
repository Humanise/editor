<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Templates
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class TestSearchTemplate extends UnitTestCase {

  function testIt() {
    $page = TestService::createTestPage(['template' => 'search']);

    $this->assertNull(SearchTemplate::load(-1));

    $obj = SearchTemplate::load($page->getId());
    $this->assertNotNull($obj);
    $obj->setTitle('My page');
    $obj->setText('Lorem ipsum dolor');
    $obj->pagesLabel = 'My label';
    $obj->pagesEnabled = true;
    $obj->save();

    $obj = SearchTemplate::load($page->getId());
    $this->assertEqual($obj->getTitle(), 'My page');
    $this->assertEqual($obj->pagesLabel, 'My label');
    $this->assertEqual($obj->pagesEnabled, true);

    $obj->setTitle('New title');
    $obj->save();

    $ctrl = new SearchTemplateController();

    $data = $ctrl->build($page->getId());
    $expected = '<search xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/search/1.0/"><title>New title</title><text>Lorem ipsum dolor</text><buttontitle></buttontitle><types><type label="My label" default="false" hidden="false" unique="page"/></types><!--dynamic--></search>';
    Log::debug($data['data']);
    $this->assertEqual($expected,$data['data']);

    TestService::removeTestPage($page);
  }
}