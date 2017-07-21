<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Validation
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class TestUIValidation extends UnitTestCase {

  function testValidateUI() {
    $dir = FileSystemService::getFullPath('Editor');
    $files = FileSystemService::findFiles($dir,['end' => '.ui.xml']);
    foreach ($files as $file) {
      $this->validateFile($file);
    }
    // TODO Validate UI from parts etc.
  }

  function testValidateParts() {
    $ctrls = ClassService::getBySuper('PartController');
    foreach ($ctrls as $ctrlName) {
      $controller = new $ctrlName();
      $part = $controller->createPart();
      $context = new PartContext();
      $ui = $controller->getEditorUI($part, $context);
      if (!empty($ui)) {
        $gui = '<?xml version="1.0" encoding="UTF-8"?><gui xmlns="uri:hui">' . $ui . '</gui>';
        $result = $this->validate($gui, $ctrlName);
      }
      $part->remove();
      $toolbars = $controller->getToolbars();
      if ($toolbars) {
        foreach ($toolbars as $title => $body) {
          $gui = '<?xml version="1.0" encoding="UTF-8"?><gui xmlns="uri:hui"><toolbar>' . $body . '</toolbar></gui>';
          $this->validate($gui, $ctrlName . '#toolbar');
        }
      }
    }
  }

  function testValidateHUITests() {
    $dir = FileSystemService::getFullPath('hui/test/');
    $files = FileSystemService::findFiles($dir,['end' => '.xml']);
    foreach ($files as $file) {
      $this->validateFile($file);
    }
    // TODO Validate UI from parts etc.
  }

  private function validate($xml, $name) {
    $result = [];
    libxml_use_internal_errors(true);
    $doc = new DOMDocument();
    $doc->loadXML($xml);
    $xsd = FileSystemService::getFullPath('hui/xslt/schema.xsd');
    $valid = $doc->schemaValidate($xsd);
    $this->assertTrue($valid,'The UI for ' . $name . ' is not valid HUI syntax');
    if (!$valid) {
      $errors = libxml_get_errors();
      foreach ($errors as $error) {
        $this->assertTrue(false,$error->line . ': ' . $name . ' - ' . $error->message);
      }
      Log::debug($errors);
    }
    libxml_clear_errors();
  }

  private function validateFile($file) {
    libxml_use_internal_errors(true);
    $doc = new DOMDocument();
    $doc->load($file);

    if (!$doc->documentElement || !in_array($doc->documentElement->nodeName, ['gui', 'subgui'])) {
      return;
    }
    $xsd = FileSystemService::getFullPath('hui/xslt/schema.xsd');
    $valid = $doc->schemaValidate($xsd);
    $this->assertTrue($valid,'The UI "' . $file . '" is not valid HUI syntax');
    if (!$valid) {
      $errors = libxml_get_errors();
      foreach ($errors as $error) {
        $msg = $error->line . ': ' . FileSystemService::getFileBaseName($file) . ' - ' . $error->message;
        $this->assertTrue(false,$msg);
      }
      Log::debug($errors);
    }
    libxml_clear_errors();
  }
}
?>