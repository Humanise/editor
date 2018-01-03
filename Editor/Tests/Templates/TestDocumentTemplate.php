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

    $rowId = DocumentTemplateEditor::addRow($page->getId(),1);

    $data = $ctrl->build($page->getId());
    $structure = DocumentTemplateEditor::getStructure($page->getId());
    $this->assertEqual('<content xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/document/1.0/">' .
      '<row id="' . $rowId . '"><column id="' . $structure[1]['columns'][1]['id'] . '"></column></row>' .
      '<row id="' . $structure[2]['id'] . '"><column id="' . $structure[2]['columns'][1]['id'] . '"></column></row>' .
      '</content>',$data['data']);

    $part = HeaderPartController::createPart();

    DocumentTemplateEditor::addPartAtEnd($page->getId(),$part);

    $data = $ctrl->build($page->getId());

    $sql = "select * from document_section where page_id=@int(id)";
    $section = Database::selectFirst($sql, ['id' => $page->getId()]);

    $expected = '<content xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/document/1.0/">' .
      '<row id="' . $structure[1]['id'] . '"><column id="' . $structure[1]['columns'][1]['id'] . '"></column></row>' .
      '<row id="' . $structure[2]['id'] . '"><column id="' . $structure[2]['columns'][1]['id'] . '">' .
      '<section id="' . $section['id'] . '"><part xmlns="http://uri.in2isoft.com/onlinepublisher/part/1.0/" type="header" id="' . $part->getId() . '">' .
      '<sub><header level="1" xmlns="http://uri.in2isoft.com/onlinepublisher/part/header/1.0/"><style/>Velkommen</header></sub></part></section>' .
      '</column></row>' .
      '</content>';

    $this->assertEqual($expected,$data['data']);

    $this->assertNotNull(DocumentTemplateEditor::loadRow($rowId));
    $this->assertTrue(DocumentTemplateEditor::deleteRow($rowId));

    $this->assertNull(DocumentTemplateEditor::loadRow($rowId));
    $this->assertFalse(DocumentTemplateEditor::deleteRow($rowId));

    $structure = DocumentTemplateEditor::getStructure($page->getId());

    $firstColumn = $structure[1]['columns'][1];

    $loadedColumn = DocumentTemplateEditor::loadColumn($firstColumn['id']);
    $this->assertNotNull($loadedColumn);

    $sectionId = DocumentTemplateEditor::addSection($firstColumn['id'], $firstColumn['index'] + 1, 'text');
    $this->assertTrue($sectionId !== null);
    $this->assertTrue($sectionId > 0);

    $sectionObject = DocumentSection::load($sectionId);
    $this->assertNotNull($sectionObject);
    $this->assertEqual($sectionObject->getPageId(), $page->getId());
    $this->assertEqual($sectionObject->getType(), 'part');

    $sectionObject->setClass('my-class');
    $sectionObject->save();

    $part = TextPart::load($sectionObject->getPartId());
    $this->assertNotNull($part);

    // Test importing data into another page...
    $other = TestService::createTestPage();
    $doc = DOMUtils::parse(Strings::toUnicode($data['data']));
    $ctrl = new DocumentTemplateController();
    $ctrl->import($other->getId(), $doc);
    $otherStructure = DocumentTemplateEditor::getStructure($other->getId());
    $this->assertEqual(2, count($otherStructure));
    $partInfo = $otherStructure[2]['columns'][1]['sections'][1];
    $this->assertEqual('header', $partInfo['partType']);

    // Check that the header was imported
    $part = PartService::load($partInfo['partType'], $partInfo['partId']);
    $this->assertNotNull($part);
    $this->assertEqual('Velkommen', $part->getText());

    TestService::removeTestPage($page);
    TestService::removeTestPage($other);
  }
}