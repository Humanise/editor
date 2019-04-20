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

  function testSQL() {
    $files = $this->getPHPFiles();
    $statements = [];
    foreach ($files as $file) {
      if (Strings::endsWith($file,'/Core/Database.php')) {
        continue;
      }
      $txt = file_get_contents($file);
      if (preg_match_all('/["\']((select|insert|update|delete) [^;]+)/uism', $txt, $found)) {
        foreach ($found[1] as $sql) {
          $statements[] = ['file' => $file, 'sql' => $sql];
        }
      }
    }
    foreach ($statements as $statement) {
      $this->assertTrue(strpos($statement['sql'], "$") === false, 'Has var in sql: ' . $statement['file'] . " : " . $statement['sql']);
    }
  }


  function testDatabaseAccess() {
    $files = $this->getPHPFiles();
    foreach ($files as $file) {
      if (strpos($file, 'Editor/Tests') !== false) {
        continue;
      }
      $txt = file_get_contents($file);
      if (strpos($file, 'Editor/Classes') !== false) {
        if (strpos($file, 'Editor/Classes/Core/Database') === false) {
          foreach (['int', 'text', 'search', 'datetime', 'date', 'boolean', 'float'] as $method) {
            $this->assertTrue(strpos($txt, 'Database::' . $method) === false, 'SQL ' . $method . ': ' . $file);
          }
        }
      } else {
        $this->assertTrue(strpos($txt, 'Database::select') === false, 'Has database access: ' . $file);
        $this->assertTrue(strpos($txt, 'Database::update') === false, 'Has database access: ' . $file);
        $this->assertTrue(strpos($txt, 'Database::insert') === false, 'Has database access: ' . $file);
        $this->assertTrue(strpos($txt, 'Database::delete') === false, 'Has database access: ' . $file);
      }
    }
  }
}
?>