<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class TestObjectLink extends UnitTestCase {

  function testCreate() {
    $obj = new News();
    $obj->save();

    $link = new ObjectLink();
    $link->setObjectId($obj->getId());
    $link->setText('My link');
    $link->setType('email');
    $link->setAlternative('this is the alternative');
    $link->setPosition(5);
    $link->setTarget('_blank');
    $link->setValue('someone@somewhere.com');

    $this->assertNull($link->getId());
    $link->save();

    $this->assertNotNull($link->getId());

    $loaded = ObjectLink::load($link->getId());
    $this->assertEqual($loaded->getType(), 'email');
    $this->assertEqual($loaded->getValue(), 'someone@somewhere.com');
    $this->assertEqual($loaded->getPosition(), 5);
    $this->assertEqual($loaded->getTarget(), '_blank');
    $this->assertEqual($loaded->getAlternative(), 'this is the alternative');

    $link->setAlternative('other alternative');
    $link->setType('url');
    $link->setValue('http://www.domain.com');
    $link->setPosition(8);
    $link->setTarget('_parent');
    $link->save();

    $loaded = ObjectLink::load($link->getId());
    $this->assertEqual($loaded->getAlternative(), 'other alternative');
    $this->assertEqual($loaded->getType(), 'url');
    $this->assertEqual($loaded->getValue(), 'http://www.domain.com');
    $this->assertEqual($loaded->getPosition(), 8);
    $this->assertEqual($loaded->getTarget(), '_parent');

    // Deleting the object should also delete the link
    $obj->remove();

    $loaded = ObjectLink::load($link->getId());
    $this->assertNull($loaded);
  }
}
?>