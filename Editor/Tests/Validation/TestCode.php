<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Validation
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class TestCode extends UnitTestCase {

  function getPHPFiles() {
    $dir = FileSystemService::getFullPath('/');
    $exclude = [
      FileSystemService::getFullPath('/Editor/Libraries'),
      FileSystemService::getFullPath('/node_modules'),
      FileSystemService::getFullPath('/hui')
    ];
    $files = FileSystemService::findFiles($dir,['end' => '.php', 'exclude' => $exclude]);
    return $files;
  }

  function testNoTabs() {
    $files = $this->getPHPFiles();
    foreach ($files as $file) {
      $txt = file_get_contents($file);
      $this->assertTrue(strpos($txt, "\t") === false, 'Has tabs: ' . $file);
    }
  }

  function testWhiteSpaceAtTheEnd() {
    $files = $this->getPHPFiles();
    foreach ($files as $file) {
      $txt = file_get_contents($file);
      $this->assertFalse(preg_match('/[ ]+$/uism', $txt), 'Has whitespace: ' . $file);
    }
  }
}
?>