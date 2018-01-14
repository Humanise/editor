<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Network
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class TestPage extends UnitTestCase {

  function testProperties() {

    $page = TestService::createTestPage();

    $title = "Eat my shorts!";
    $description = "This is somthing no one will ever see";
    $keywords = "key words";
    $frameId = $page->getFrameId();
    $designId = $page->getDesignId();
    $language = "de";
    $searchable = true;
    $dynamic = true;
    $secure = true;
    $disabled = true;
    $created = time();
    $changed = time();
    $published = time();
    $data = "This is the data";
    $index = "indexed is this";
    $name = "name of the page this is";
    $path = "/path/to/somewhere";
    $nextPage = 30;
    $previousPage = 56;

    $page->setTitle($title);
    $page->setDescription($description);
    $page->setKeywords($keywords);
    $page->setFrameId($frameId);
    $page->setDesignId($designId);
    $page->setLanguage($language);
    $page->setSearchable($searchable);
    $page->setDynamic($dynamic);
    $page->setSecure($secure);
    $page->setDisabled($disabled);
    $page->setCreated($created);
    $page->setChanged($changed);
    $page->setPublished($published);
    $page->setData($data);
    $page->setIndex($index);
    $page->setName($name);
    $page->setPath($path);
    $page->setNextPage($nextPage);
    $page->setPreviousPage($previousPage);
    $page->save();

    $loaded = Page::load($page->getId());

    $this->assertEqual($title, $loaded->getTitle());
    $this->assertEqual($description, $loaded->getDescription());
    $this->assertEqual($keywords, $loaded->getKeywords());
    $this->assertEqual($frameId, $loaded->getFrameId());
    $this->assertEqual($designId, $loaded->getDesignId());
    $this->assertEqual($language, $loaded->getLanguage());
    $this->assertEqual($searchable, $loaded->getSearchable());
    $this->assertEqual($dynamic, $loaded->getDynamic());
    $this->assertEqual($secure, $loaded->getSecure());
    $this->assertEqual($disabled, $loaded->getDisabled());
    $this->assertEqual($created, $loaded->getCreated());
    $this->assertEqual($changed, $loaded->getChanged());
    $this->assertEqual($published, $loaded->getPublished());
    $this->assertEqual($data, $loaded->getData());
    $this->assertEqual($index, $loaded->getIndex());
    $this->assertEqual($name, $loaded->getName());
    $this->assertEqual($path, $loaded->getPath());
    $this->assertEqual($nextPage, $loaded->getNextPage());
    $this->assertEqual($previousPage, $loaded->getPreviousPage());

    $this->assertEqual('document', $loaded->getTemplateUnique());

    TestService::removeTestPage($page);
  }

  function testCreate() {
    $template = TemplateService::getTemplateByUnique('document');
    if (!$template) {
      Log::debug('Skipping test since no document template exists');
      return;
    }

    $hierarchy = new Hierarchy();
    $hierarchy->save();

    $frame = new Frame();
    $frame->setHierarchyId($hierarchy->getId());
    $frame->save();

    $design = new Design();
    $design->setUnique('custom');
    $design->save();

    $page = new Page();

    $this->assertNull($page->getId());
    $this->assertFalse(PageService::validate($page));
    $this->assertFalse($page->create());

    $page->setTemplateId($template->getId());
    $this->assertFalse(PageService::validate($page));
    $this->assertFalse($page->create());

    $page->setDesignId($design->getId());
    $this->assertFalse(PageService::validate($page));
    $this->assertFalse($page->create());

    $page->setFrameId($frame->getId());
    $this->assertFalse(PageService::validate($page));
    $this->assertFalse($page->create());

    $page->setTitle('Test page');
    $this->assertTrue(PageService::validate($page));

    $page->setDescription('My page description, find this: djsakJSDLjdasljslsdjljdslJ');
    $page->setPath('test/path.html');
    $page->setLanguage('en');

    $countBefore = PageService::getLatestPageCount();
    $page->create();
    $countAfter = PageService::getLatestPageCount();
    $this->assertEqual($countBefore + 1, $countAfter);

    $this->assertTrue(PageService::exists($page->getId()));

    $loaded = Page::load($page->getId());
    $this->assertNotNull($loaded);

    $this->assertEqual($page->getId(),$loaded->getId());
    $this->assertEqual($page->getTemplateId(),$loaded->getTemplateId());
    $this->assertEqual($page->getDesignId(),$loaded->getDesignId());
    $this->assertEqual($page->getFrameId(),$loaded->getFrameId());
    $this->assertEqual($page->getTitle(),$loaded->getTitle());
    $this->assertEqual($page->getDescription(),$loaded->getDescription());
    $this->assertEqual($page->getPath(),$loaded->getPath());
    $this->assertEqual($page->getLanguage(),$loaded->getLanguage());

    // Check changed
    $this->assertFalse(PageService::isChanged($page->getId()));
    $page->setTitle('Test page');
    $page->save();
    $this->assertTrue(PageService::exists($page->getId()));
    sleep(1);
    PageService::markChanged($page->getId());
    $this->assertTrue(PageService::isChanged($page->getId()));

    $page->publish();
    $this->assertFalse(PageService::isChanged($page->getId()));

    // History
    $history = PageService::listHistory($page->getId());
    $this->assertEqual(1, count($history));
    PageService::updateHistoryMessage($history[0]['id'], 'This is a message');
    $this->assertEqual('This is a message', PageService::getHistoryMessage($history[0]['id']));
    PageService::createPageHistory($page->getId(), $page->getData());
    $this->assertEqual(2, count(PageService::listHistory($page->getId())));

    // Check that the design and frame cannot be removed
    $this->assertFalse($design->canRemove());
    $this->assertFalse($design->remove());
    $this->assertFalse($frame->canRemove());
    $this->assertFalse($frame->remove());

    $part = new TextPart();
    $part->setText('this should be indexed');
    $part->save();
    DocumentTemplateEditor::addPartAtEnd($page->getId(), $part);

    $page->publish();

    // Index
    $index = PageService::getIndex($page->getId());
    $this->assertTrue(strpos($index, 'this should be indexed') !== false);

    $linkText = PageService::getLinkText($page->getId());
    $this->assertTrue(strpos($linkText, 'this should be indexed') !== false);

    $result = PageQuery::rows()->withText('djsakJSDLjdasljslsdjljdslJ')->search();
    $this->assertEqual($result->getTotal(),'1');
    $list = $result->getList();
    $first = $list[0];
    $this->assertEqual($first['title'],'Test page');
    $this->assertEqual($first['id'],$page->getId());
    $this->assertEqual($first['language'],$page->getLanguage());

    $page->delete();

    // Test that it is gone
    $loaded = Page::load($page->getId());
    $this->assertNull($loaded);
    // Check that the history is gone
    $this->assertEqual(0, count(PageService::listHistory($page->getId())));


    Log::debug('Design id: ' . $design->getId());
    $this->assertTrue($design->canRemove());
    $this->assertTrue($design->remove());
    $this->assertFalse(Design::load($design->getId()));

    $this->assertTrue($frame->canRemove());
    $this->assertTrue($frame->remove());
    $hierarchy->remove();
  }

  function testLinks() {
    // Create two pages
    $fromPage = TestService::createTestPage();
    $toPage = TestService::createTestPage();

    // They should not be changed now
    $this->assertFalse(PageService::isChanged($fromPage->getId()));
    $this->assertFalse(PageService::isChanged($toPage->getId()));

    // Create news with link to a page
    $news = new News();
    $news->setTitle('Unit test news article');
    $news->save();
    ObjectLinkService::addLink($news,'Read it','This is the alternative',null,'page',$toPage->getId());
    $news->publish();

    // Check that the news is not changed
    $this->assertFalse(ObjectService::isChanged($news->getId()));

    // Create a link from one to the other
    $link = new Link();
    $link->setText('dummy');
    $link->setPageId($fromPage->getId());
    $link->setTypeAndValue('page',$toPage->getId());
    $link->save();

    // Check that the links
    $links = LinkService::getPageLinks($fromPage->getId());
    $this->assertEqual(count($links),1);

    // Wait a little to make timestamps different
    sleep(1);

    // Save the destination
    $toPage->save();

    // Now the source of the link should be changed
    $this->assertTrue(PageService::isChanged($fromPage->getId()));

    // Now the news should be changed
    $this->assertTrue(ObjectService::isChanged($news->getId()));


    // Remove the two pages
    TestService::removeTestPage($fromPage);
    TestService::removeTestPage($toPage);

    $news->remove();

    // Check that the links are removed
    $links = LinkService::getPageLinks($fromPage->getId());
    $this->assertEqual(count($links),0);
  }

  function TestRendering() {
    $page = TestService::createTestPage();

    $url = ConfigurationService::getCompleteBaseUrl() . '?id=' . $page->getId();

    $response = HttpClient::send(new WebRequest($url));
    $this->assertEqual($response->getStatusCode(),200);

    $page->setDisabled(true);
    $page->save();

    $response = HttpClient::send(new WebRequest($url));
    $this->assertEqual($response->getStatusCode(),404);

    TestService::removeTestPage($page);
  }

  function TestTranslations() {
    $english = TestService::createTestPage();
    $english->setLanguage('en');
    $english->save();

    $danish = TestService::createTestPage();
    $danish->setLanguage('da');
    $danish->save();

    $german = TestService::createTestPage();
    $german->setLanguage('de');
    $german->save();

    PageService::addPageTranslation($english->getId(), $danish->getId());
    PageService::addPageTranslation($english->getId(), $german->getId());
    PageService::addPageTranslation($english->getId(), $german->getId());
    $englishTranslations = PageService::getPageTranslationList($english->getId());
    $this->assertEqual(2, count($englishTranslations));

    $this->assertTrue(PageService::removePageTranslation($englishTranslations[0]['id']));

    $englishTranslations = PageService::getPageTranslationList($english->getId());
    $this->assertEqual(1, count($englishTranslations));

    TestService::removeTestPage($english);
    TestService::removeTestPage($danish);
    TestService::removeTestPage($german);
  }
}
?>