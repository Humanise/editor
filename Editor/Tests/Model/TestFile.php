<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class TestFile extends AbstractObjectTest {

  function TestFile() {
    parent::AbstractObjectTest('file');
  }

  function testProperties() {
    $now = time();

    $randomMime = Strings::generate();

    $file = new File();
    $file->setTitle('My file');
    $file->setMimetype($randomMime);
    $file->save();

    $otherFiler = new File();
    $otherFiler->setTitle('My other file');
    $otherFiler->save();

    $loaded = File::load($file->getId());
    $this->assertEqual($loaded->getTitle(),'My file');

    $group1 = new Filegroup();
    $group1->save();

    $group2 = new Filegroup();
    $group2->save();

    $group3 = new Filegroup();
    $group3->save();

    $groupOther = new Filegroup();
    $groupOther->save();

    $loaded->updateGroupIds([$group1->getId(), $group2->getId()]);
    $loaded->addGroupId($group3->getId());
    $loaded->addGroupId($group3->getId());
    $groupIds = $loaded->getGroupIds();

    $this->assertEqual(3, count($groupIds));


    $all = Query::after('File')->get();
    $this->assertTrue(count($all) > 1);

    $found = Query::after('File')->withCustom('group', $group1->getId())->get();
    $this->assertEqual(1, count($found));

    $found = Query::after('File')->withCreatedMin($now - 10)->get();
    $this->assertEqual(2, count($found));

    $found = Query::after('File')->withCustom('mimetype', [$randomMime])->get();
    $this->assertEqual(1, count($found));

    $otherFiler->remove();
    $loaded->remove();
    $group1->remove();
    $group2->remove();
    $group3->remove();
    $groupOther->remove();
  }
}
?>