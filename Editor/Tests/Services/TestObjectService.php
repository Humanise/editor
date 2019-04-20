<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Services
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class TestObjectService extends UnitTestCase {

  function testValidIds() {

    $this->assertEqual(0, count(ObjectService::getValidIds([])));

    $news1 = new News();
    $news1->save();

    $news2 = new News();
    $news2->save();

    $ids = ObjectService::getValidIds([$news1->getId(), $news2->getId(), -1, 0, $news2->getId() + 10]);
    $this->assertEqual(2, count($ids));

    $this->assertEqual($news2->getId(), ObjectService::getLatestId('news'));

    $news1->remove();
    $news2->remove();
  }
}
?>