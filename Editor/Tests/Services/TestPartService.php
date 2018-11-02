<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Services
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class TestPartService extends UnitTestCase {

  function testStuff() {
    $this->assertEqual('HtmlPart',PartService::getClassName('html'));
    $this->assertNull(PartService::getClassName(''));
  }

  /** Test link functionality of part service */
  function testLinkText() {
    $text = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit';

    $part = new TextPart();
    $part->setText($text);
    $part->save();

    $linkText = PartService::getLinkText($part->getId());

    $this->assertEqual($text, $linkText);

    $part->remove();
  }

  /** Test link functionality of part service */
  function testLinks() {
    $part = new PosterPart();
    $part->save();

    $link = new PartLink();
    $link->setPartId($part->getId());
    $link->setSourceType('A');
    $link->setTargetType('B');
    $link->setTargetValue('C');
    $link->setSourceText('Fringilla');
    $link->save();

    $loadedLinks = LinkService::getPartLinks($part->getId());

    $this->assertEqual(count($loadedLinks),1);

    $loaded = $loadedLinks[0];

    $this->assertTrue($link->getId() > 0);
    $this->assertEqual($loaded->getSourceType(),$link->getSourceType());
    $this->assertEqual($loaded->getTargetType(),$link->getTargetType());
    $this->assertEqual($loaded->getTargetValue(),$link->getTargetValue());
    $this->assertEqual($loaded->getSourceText(),$link->getSourceText());

    // Modify the link
    $link->setSourceType('X');
    $link->setTargetType('Y');
    $link->setTargetValue('Z');
    $link->save();

    $loadedLinks = LinkService::getPartLinks($part->getId());

    $this->assertEqual(count($loadedLinks),1);

    $loaded = $loadedLinks[0];

    $this->assertTrue($link->getId() > 0);
    $this->assertEqual($loaded->getSourceType(),$link->getSourceType());
    $this->assertEqual($loaded->getTargetType(),$link->getTargetType());
    $this->assertEqual($loaded->getTargetValue(),$link->getTargetValue());
    $this->assertEqual($loaded->getSourceText(),$link->getSourceText());

    $this->assertEqual(PartService::getSingleLink($part)['id'], $link->getId());
    $this->assertEqual(PartService::getSingleLink($part, 'X')['id'], $link->getId());
    $this->assertNull(PartService::getSingleLink($part, 'NONE'));


    PartService::removeLinks($part);

    $this->assertEqual(count(LinkService::getPartLinks($part->getId())),0);
    $part->remove();
  }
}
?>