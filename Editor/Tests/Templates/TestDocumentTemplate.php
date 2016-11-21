<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Templates
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestDocumentTemplate extends UnitTestCase {

	function testIt() {
		$page = TestService::createTestPage();

		$ctrl = new DocumentTemplateController();

		$data = $ctrl->build($page->getId());
    $structure = DocumentTemplateEditor::getStructure($page->getId());
		$this->assertEqual('<content xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/document/1.0/">' .
      '<row id="' . $structure[1]['id'] . '"><column id="' . $structure[1]['columns'][1]['id'] . '"></column></row>' .
      '</content>',$data['data']);

		DocumentTemplateEditor::addRow($page->getId(),1);

		$data = $ctrl->build($page->getId());
    $structure = DocumentTemplateEditor::getStructure($page->getId());
		$this->assertEqual('<content xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/document/1.0/">' .
      '<row id="' . $structure[1]['id'] . '"><column id="' . $structure[1]['columns'][1]['id'] . '"></column></row>' .
      '<row id="' . $structure[2]['id'] . '"><column id="' . $structure[2]['columns'][1]['id'] . '"></column></row>' .
      '</content>',$data['data']);

		$part = HeaderPartController::createPart();

		DocumentTemplateEditor::addPartAtEnd($page->getId(),$part);

		$data = $ctrl->build($page->getId());

		$sql = "select * from document_section where page_id=@int(id)";
		$section = Database::selectFirst($sql, ['id' => $page->getId()]);

		$expected = '<content xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/document/1.0/">'.
			'<row id="' . $structure[1]['id'] . '"><column id="' . $structure[1]['columns'][1]['id'] . '"></column></row>'.
			'<row id="' . $structure[2]['id'] . '"><column id="' . $structure[2]['columns'][1]['id'] . '">'.
			'<section id="' . $section['id'] . '"><part xmlns="http://uri.in2isoft.com/onlinepublisher/part/1.0/" type="header" id="'.$part->getId().'">' .
			'<sub><header level="1" xmlns="http://uri.in2isoft.com/onlinepublisher/part/header/1.0/"><style/>Velkommen</header></sub></part></section>' .
			'</column></row>'.
			'</content>';

		$this->assertEqual($expected,$data['data']);

		TestService::removeTestPage($page);
	}
}