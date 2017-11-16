<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class TestEvent extends AbstractObjectTest {

  function TestEvent() {
    parent::AbstractObjectTest('event');
  }

  function makeValid($event) {
    $event->setStartdate(time());
    $event->setEnddate(time());
  }

  function testIt() {

    $now = time();

    $event = new Event();
    $event->setStartdate($now);
    $event->setEnddate($now);
    $event->save();

    $event2 = new Event();
    $event2->setStartdate($now + 10);
    $event2->setEnddate($now + 10);
    $event2->save();

    $cal1 = new Calendar();
    $cal1->setTitle('My calendar');
    $cal1->save();

    $cal2 = new Calendar();
    $cal2->setTitle('My calendar');
    $cal2->save();

    $event->updateCalendarIds([$cal1->getId(), $cal2->getId()]);
    $calIds = $event->getCalendarIds();
    $this->assertEqual(2, count($calIds));

    $found = Event::search(['calendarId' => $cal1->getId()]);
    $this->assertEqual(1, count($found));
    $this->assertEqual($event->getId(), $found[0]->getId());

    $found = Event::getSimpleEvents(['calendarId' => $cal1->getId()]);
    $this->assertEqual(1, count($found));
    $this->assertEqual($event->getId(), $found[0]['id']);

    $found = Event::search(['startDate' => $now + 10, 'endDate' => $now + 10]);
    $this->assertEqual(1, count($found));
    $this->assertEqual($event2->getId(), $found[0]->getId());

    $found = Event::getSimpleEvents(['startDate' => $now + 10, 'endDate' => $now + 10]);
    $this->assertEqual(1, count($found));
    $this->assertEqual($event2->getId(), $found[0]['id']);

    $event->remove();
    $event2->remove();
    $cal1->remove();
    $cal2->remove();
  }
}
?>