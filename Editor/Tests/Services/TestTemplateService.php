<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Services
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class TestTemplateService extends UnitTestCase {

  function testIt() {
    $available = TemplateService::getAvailableTemplates();
    $this->assertEqual(6, count($available));

    $installed = TemplateService::getInstalledTemplates();
    $this->assertTrue(count($installed) > 0);

    $first = Template::load($installed[0]['id']);
    $this->assertNotNull($first);
    $this->assertEqual($installed[0]['unique'], $first->getUnique());

    Log::debug($installed);
  }

}
?>