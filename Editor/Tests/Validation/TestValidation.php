<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Validation
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class TestValidation extends UnitTestCase {

  function testValidateDesigns() {
    $template = TemplateService::getTemplateByUnique('document');
    if (!$template) {
      Log::debug('Skipping test since no document template exists');
      return;
    }

    $hierarchy = new Hierarchy();
    $hierarchy->create();

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
    $page->setTitle('Test page for validation');
    $page->setLanguage('en');
    $page->create();

    $page->publish();
    $this->assertFalse(PageService::isChanged($page->getId()));

    $loaded = Page::load($page->getId());
    $this->assertNotNull($loaded);

    $validatorCommand = ConfigurationService::getValidatorCommand();
    $this->assertNotNull($validatorCommand, 'No validator');

    $designs = DesignService::getAvailableDesigns();
    foreach ($designs as $name => $info) {
      $url = ConfigurationService::getCompleteBaseUrl() . '?id=' . $page->getId() . '&design=' . $name . '&dev=false';
      Log::debug('Checking: ' . $url);
      $file = new RemoteFile($url);

      $tmp = $file->writeToTempFile();
      $cmd = $validatorCommand . " --format json " . $tmp;
      $result = ShellService::executeLive($cmd);
      $out = $result['output'];
      $out = preg_replace('/Picked up[^\\n]+\\n/uism', '', $out);
      $validation = Strings::fromJSON($out);
      if (is_object($validation) && isset($validation->messages) && is_array($validation->messages) && count($validation->messages) > 0) {
        foreach ($validation->messages as $msg) {
          $this->assertTrue(false, $name . ': ' . $msg->message . ' : ' . $msg->extract);
        }
        Log::debug($validation);
        break;
      }
      unlink($tmp);

      $html = $file->getData();
      $this->assertTrue(strpos($html, '<!DOCTYPE html>') !== false,'The design "' . $name . '" has wrong doctype');
      $this->assertTrue(strpos($html, '<meta http-equiv="content-type" content="text/html; charset=utf-8"/>') !== false,'The design "' . $name . '" has no content-type');
      $this->assertTrue(strpos($html, '<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">') !== false,'The design "' . $name . '" does not have correct html tag');
      $this->assertFalse(strpos($html, 'http://uri.in2isoft.com') !== false,'The design "' . $name . '" may contain xml namespaces');
      $this->assertTrue(strpos($html, 'Test page for validation') !== false,'The design "' . $name . '" does not contain the title');
      if (isset($info->build)) {
        $this->assertTrue(strpos($html, '/js/script.js') !== false,'The design "' . $name . '" does not include scripts');
        if ($name !== 'in2isoft') {
          $this->assertTrue(strpos($html, '/css/style.css') !== false,'The design "' . $name . '" does not include css');
        }
      } else {
        if (isset($info->js)) {
          $this->assertTrue(strpos($html, '/api/style/' . $name . '.js') !== false,'The design "' . $name . '" does not include JS from API');
        }
        if (isset($info->css)) {
          $this->assertTrue(strpos($html, '/api/style/' . $name . '.css') !== false,'The design "' . $name . '" does not include css from API');
        }
      }
      $this->assertTrue(XmlService::validateSnippet($html),'The design "' . $name . '" is not valid xml');
    }

    $page->delete();

    // Test that it is gone
    $loaded = Page::load($page->getId());
    $this->assertNull($loaded);

    $this->assertTrue($design->canRemove());
    $this->assertTrue($design->remove());
    $this->assertTrue($frame->canRemove());
    $this->assertTrue($frame->remove());
    $this->assertTrue($hierarchy->delete());
  }
}

function liveExecuteCommand($cmd)
{

    while (@ ob_end_flush()); // end all output buffers if any

    $proc = popen("$cmd 2>&1 ; echo Exit status : $?", 'r');

    $live_output = "";
    $complete_output = "";

    while (!feof($proc)) {
      $live_output = fread($proc, 4096);
      $complete_output = $complete_output . $live_output;
      echo "$live_output";
      @ flush();
    }

    pclose($proc);

    // get exit status
    preg_match('/[0-9]+$/', $complete_output, $matches);

    // return exit status and intended output
    return  [
      'status' => intval($matches[0]),
      'output' => str_replace("Exit status : " . $matches[0], '', $complete_output)
    ];
}
?>