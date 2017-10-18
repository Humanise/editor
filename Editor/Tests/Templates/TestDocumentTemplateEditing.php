<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Templates
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class TestDocumentTemplateEditing extends UnitTestCase {

  function testRenderRowStyle() {
    $style = '<if max-width="20px"><component name="row"><width of="50%"/></component></if>';
    $css = DocumentTemplateController::renderRowStyle(10, $style);
    Log::debug($css);
    $this->assertEqual('@media screen and (max-width: 20px){.document_row-10{width:50%;}}', $css);
  }
}