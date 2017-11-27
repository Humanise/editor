<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}
require_once(FileSystemService::getFullPath('Editor/Libraries/simpletest/unit_tester.php'));
require_once(FileSystemService::getFullPath('Editor/Libraries/simpletest/reporter.php'));
require_once(FileSystemService::getFullPath('Editor/Classes/Tests/AbstractObjectTest.php'));

class TestService {

  static function getResourceUrl($name) {
    $url = ConfigurationService::getBaseUrl() . 'Editor/Tests/Resources/' . $name;
    if ($url[0] == '/') {
      $url = 'http://localhost' . $url;
    }
    return $url;
  }

  static function getGroups() {
    $out = [];
    $groups = FileSystemService::listDirs(FileSystemService::getFullPath('Editor/Tests/'));
    for ($i = 0; $i < count($groups); $i++) {
      if ($groups[$i] != 'Resources') {
        $out[] = $groups[$i];
      }
    }
    return $out; //$groups;
  }

  static function getTestsInGroup($group) {
    return FileSystemService::listFiles(FileSystemService::getFullPath('Editor/Tests/' . $group . '/'));
  }

  static function runTest($test,$reporter = null) {
    $path = FileSystemService::getFullPath('Editor/Tests/' . $test . '.php');
    $test = new TestSuite($test);
    $test->addFile($path);
    if ($reporter == null) {
      $reporter = new HtmlReporter();
    }
    $test->run($reporter);
  }

  static function runTestByName($name, $reporter = null) {
    if ($reporter == null) {
      $reporter = new HtmlReporter();
    }
    $groups = TestService::getGroups();
    $test = new TestSuite($name);
    foreach ($groups as $group) {
      $path = FileSystemService::getFullPath('Editor/Tests/' . $group . '/' . $name . '.php');
      if (file_exists($path)) {
        $test->addFile($path);
      }
    }
    $test->run($reporter);
  }

  static function runTestsInGroup($group,$reporter = null) {
    $paths = [];

    $tests = TestService::getTestsInGroup($group);
    foreach ($tests as $test) {
      $paths[] = FileSystemService::getFullPath('Editor/Tests/' . $group . '/' . $test);
    }
    if ($reporter == null) {
      $reporter = new HtmlReporter();
    }

    $test = new TestSuite($group);
    foreach ($paths as $path) {
      $test->addFile($path);
    }
    $test->run($reporter);
  }

  static function runAllTests($reporter = null) {
    $paths = [];
    $groups = TestService::getGroups();

    foreach ($groups as $group) {
      $tests = TestService::getTestsInGroup($group);
      foreach ($tests as $test) {
        $paths[] = FileSystemService::getFullPath('Editor/Tests/' . $group . '/' . $test);
      }
    }
    $test = new TestSuite('All tests');
    foreach ($paths as $path) {
      $test->addFile($path);
    }
    if ($reporter == null) {
      $reporter = new HtmlReporter();
    }
    $test->run($reporter);
  }

  static function createTestPage($options = []) {
    $templateKey = 'document';
    if (isset($options['template'])) {
      $templateKey = $options['template'];
    }
    $template = TemplateService::getTemplateByUnique($templateKey);
    if (!$template) {
      TemplateService::install($templateKey);
      $template = TemplateService::getTemplateByUnique($templateKey);
    }
    if (!$template) {
      return null;
    }
    Log::debug($template);

    $hierarchy = new Hierarchy();
    $hierarchy->save();

    $frame = new Frame();
    $frame->setHierarchyId($hierarchy->getId());
    $frame->save();

    $design = new Design();
    $design->setUnique('custom');
    $design->save();

    $page = new Page();

    $page->setTemplateId($template->getId());
    $page->setDesignId($design->getId());
    $page->setFrameId($frame->getId());

    $page->setTitle('Test page');
    $page->create();

    return $page;
  }

  static function removeTestPage($page) {
    $frame = Frame::load($page->getFrameId());
    $hierarchy = Hierarchy::load($frame->getHierarchyId());
    $design = Design::load($page->getDesignId());

    $hierarchy->remove();
    $page->remove();
    $frame->remove();
    $design->remove();
  }

}