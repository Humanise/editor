<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Utilities
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class TestDates extends UnitTestCase {

  function testParse() {
    $date = '15-04-1980';
    $stamp = Dates::parse($date);
    $this->assertEqual(Dates::formatLongDate($stamp,'en_US'),'15. Apr 1980');

    $date = '15/04-1980';
    $stamp = Dates::parse($date);
    $this->assertEqual(Dates::formatLongDate($stamp,'en_US'),'15. Apr 1980');

    $date = '15.04/1980';
    $stamp = Dates::parse($date);
    $this->assertEqual(Dates::formatLongDate($stamp,'en_US'),'15. Apr 1980');

    $date = '1980-04-15';
    $stamp = Dates::parse($date);
    $this->assertEqual(Dates::formatLongDate($stamp,'en_US'),'15. Apr 1980');

    $date = '1980-4-15';
    $stamp = Dates::parse($date);
    $this->assertEqual(Dates::formatLongDate($stamp,'en_US'),'15. Apr 1980');

    $date = '15.04/10';
    $stamp = Dates::parse($date);
    $this->assertEqual(Dates::formatLongDate($stamp,'en_US'),'15. Apr 2010');

    $date = '150410';
    $stamp = Dates::parse($date);
    $this->assertEqual(Dates::formatLongDate($stamp,'en_US'),'15. Apr 2010');
  }

  function testFormatting() {
    $date = '2010-12-06T08:12:42-08:00';
    $stamp = Dates::parseRFC3339($date);
    $this->assertEqual(Dates::formatDateTime($stamp, 'en_US'), '6. Dec kl. 17:12');
    $this->assertEqual(Dates::formatLongDateTimeGM($stamp, 'en_US'), '6. Dec 2010 kl. 16:12');
    $this->assertEqual(Dates::formatLongDateTime($stamp, 'en_US'), '6. Dec 2010 kl. 17:12');
    $this->assertEqual(Dates::formatShortTime($stamp, 'en_US'), '17:12');
    $this->assertEqual(Dates::formatLongDate($stamp, 'en_US'), '6. Dec 2010');
    $this->assertEqual(Dates::formatShortDate($stamp), '6. Dec');
    $this->assertEqual(Dates::formatDate($stamp, 'en_US'), '6. December 2010');
  }

  function testParseRFC3339() {
    $date = '2010-12-06T08:12:42-08:00';
    $stamp = Dates::parseRFC3339($date);
    $this->assertEqual(Dates::formatLongDateTimeGM($stamp,'en_US'),'6. Dec 2010 kl. 16:12');
  }

  function testParseRFC3339more() {
    $date = '2016-07-02T12:52:54+02:00';
    $stamp = Dates::parseRFC3339($date);
    $this->assertEqual(Dates::formatLongDateTimeGM($stamp,'en_US'),'2. Jul 2016 kl. 10:52');
  }

  function testParseRFC3339_greenwich() {
    $date = '2010-12-06T08:12:42Z';
    $stamp = Dates::parseRFC3339($date);
    $this->assertEqual(Dates::formatLongDateTimeGM($stamp,'en_US'),'6. Dec 2010 kl. 08:12');
  }
}