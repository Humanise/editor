<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class TestCalendarsource extends AbstractObjectTest {

  function TestCalendarsource() {
    parent::AbstractObjectTest('calendarsource');
  }

  function testProperties() {
    $obj = new Calendarsource();
    $obj->setTitle('My source');
    $obj->save();

    $loaded = Calendarsource::load($obj->getId());
    $this->assertEqual($loaded->getTitle(),'My source');

    $loaded->remove();
  }

  function testItems() {
    $url = TestService::getResourceUrl('ical.ics');
    $obj = new Calendarsource();
    $obj->setUrl($url);
    $obj->setTitle('My source');
    $obj->save();

    $this->assertFalse($obj->isInSync());
    $this->assertNull($obj->getSynchronized());

    $obj->synchronize();

    $obj = Calendarsource::load($obj->getId());

    $this->assertNotNull($obj->getSynchronized());
    $this->assertFalse($obj->isInSync());

    $events = $obj->getEvents();
    $this->assertEqual(count($events), 3);

    $first = $events[0];
    $this->assertEqual("This is the note\næøå\nHep hey", $first['description']);
    $this->assertEqual("Ny begivenhed", $first['summary']);

    $obj->remove();

    $events = $obj->getEvents();
    $this->assertEqual(count($events), 0);
  }
}
?>