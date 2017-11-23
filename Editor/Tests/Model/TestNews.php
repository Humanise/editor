<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class TestNews extends AbstractObjectTest {

  function TestNews() {
    parent::AbstractObjectTest('news');
  }

  function testProperties() {
    $news = new News();
    $news->setTitle('My news');
    $news->save();

    $otherNews = new News();
    $otherNews->setTitle('My other news');
    $otherNews->save();

    $loaded = News::load($news->getId());
    $this->assertEqual($loaded->getTitle(),'My news');


    $group1 = new Newsgroup();
    $group1->save();

    $group2 = new Newsgroup();
    $group2->save();

    $group3 = new Newsgroup();
    $group3->save();


    $news->updateGroupIds([$group1->getId(), $group2->getId()]);
    $news->addGroupId($group3->getId());
    $news->addGroupId($group3->getId());
    $groupIds = $news->getGroupIds();

    $this->assertEqual(3, count($groupIds));

    $group2->remove();

    $this->assertEqual(2, count($news->getGroupIds()));

    $news->remove();
    $otherNews->remove();
    $group1->remove();
    $group3->remove();
  }
}
?>