<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class TestImage extends AbstractObjectTest {

  function TestImage() {
    parent::AbstractObjectTest('image');
  }

  function testProperties() {
    $image = new Image();
    $image->setTitle('My photo');
    $image->save();

    $otherImage = new Image();
    $otherImage->setTitle('My other photo');
    $otherImage->save();

    $loaded = Image::load($image->getId());
    $this->assertEqual($loaded->getTitle(),'My photo');


    $group1 = new Imagegroup();
    $group1->save();

    $group2 = new Imagegroup();
    $group2->save();

    $group3 = new Imagegroup();
    $group3->save();


    $image->changeGroups([$group1->getId(), $group2->getId()]);
    $image->addGroupId($group3->getId());
    $image->addGroupId($group3->getId());
    $groupIds = $image->getGroupIds();

    $this->assertEqual(3, count($groupIds));

    $group2->remove();

    $this->assertEqual(2, count($image->getGroupIds()));

    $image->remove();
    $otherImage->remove();
    $group1->remove();
    $group3->remove();
  }
}
?>