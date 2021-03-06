<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class TestFrame extends UnitTestCase {

  function testProperties() {
    $hierarchy = new Hierarchy();
    $hierarchy->setName('My test hierarchy');
    $hierarchy->save();
    $this->assertNotNull($hierarchy->getId());

    $obj = new Frame();
    $obj->setName('My frame name');
    $obj->setTitle('My frame title');
    $obj->setBottomText('This is the bottom text');
    $this->assertFalse($obj->save(),'Should not be able to save until a hierarchy is set');
    $this->assertFalse($obj->isPersistent());
    $obj->setHierarchyId($hierarchy->getId());
    $obj->save();
    $this->assertTrue($obj->isPersistent());

    $loaded = Frame::load($obj->getId());
    $this->assertNotNull($loaded);
    $this->assertEqual($loaded->getId(),$obj->getId());
    $this->assertEqual($loaded->getName(),'My frame name');
    $this->assertEqual($loaded->getTitle(),'My frame title');
    $this->assertEqual($loaded->getBottomText(),'This is the bottom text');
    $this->assertEqual($loaded->getHierarchyId(),$hierarchy->getId());
    $this->assertTrue($loaded->getChanged() <= time());

    FrameService::replaceLinks($obj,[(object)['kind'=>'email','value'=>'test@mail.com','text'=>'Mail me']],[]);

    $topLinks = FrameService::getLinks($obj, 'top');
    $this->assertEqual(1, count($topLinks));
    $this->assertEqual('test@mail.com', $topLinks[0]['value']);
    $this->assertEqual('Mail me', $topLinks[0]['text']);

    $this->assertEqual(0, count(FrameService::getLinks($obj, 'bottom')));

    FrameService::replaceLinks($obj,[],[(object)['kind'=>'url','value'=>'http://domain.com/','text'=>'Visit me']]);

    $bottomLinks = FrameService::getLinks($obj, 'bottom');
    $this->assertEqual(1, count($bottomLinks));
    $this->assertEqual('http://domain.com/', $bottomLinks[0]['value']);
    $this->assertEqual('Visit me', $bottomLinks[0]['text']);

    $this->assertEqual(0, count(FrameService::getLinks($obj, 'top')));

    $loaded->remove();

    $hierarchy->remove();

    $this->assertNull(Frame::load($obj->getId()));
  }
}
?>