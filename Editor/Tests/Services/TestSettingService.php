<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Services
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class TestSettingService extends UnitTestCase {

  function testSettings() {
    $domain = 'testing';
    $subdomain = 'stuff';
    $key = 'mykey';
    $value = 'myvalue';
    $user = 1;
    $this->assertEqual(null, SettingService::getSetting($domain,$subdomain,$key,$user));
    SettingService::setSetting($domain,$subdomain,$key,$value,$user);
    $this->assertEqual('myvalue', SettingService::getSetting($domain,$subdomain,$key,$user));
    SettingService::setSetting($domain,$subdomain,$key,null,$user);
    $this->assertEqual(null, SettingService::getSetting($domain,$subdomain,$key,$user));
  }
}
?>