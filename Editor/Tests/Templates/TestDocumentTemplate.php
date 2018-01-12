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

    $sectionAtEndId = DocumentTemplateEditor::addPartAtEnd($page->getId(),$part);
    $this->assertTrue($sectionAtEndId > 0);

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

    $this->assertNotNull(DocumentRow::load($rowId));
    $this->assertTrue(DocumentTemplateEditor::deleteRow($rowId));

    $this->assertNull(DocumentRow::load($rowId));
    $this->assertFalse(DocumentTemplateEditor::deleteRow($rowId));

    $structure = DocumentTemplateEditor::getStructure($page->getId());

    $firstColumn = $structure[1]['columns'][1];

    $loadedColumn = DocumentColumn::load($firstColumn['id']);
    $this->assertNotNull($loadedColumn);

    $sectionId = DocumentTemplateEditor::addSection($firstColumn['id'], $firstColumn['index'] + 1, 'text');
    $this->assertTrue($sectionId !== null);
    $this->assertTrue($sectionId > 0);

    // Load the section
    $sectionObject = DocumentSection::load($sectionId);
    $this->assertNotNull($sectionObject);
    $this->assertEqual($sectionObject->getPageId(), $page->getId());
    $this->assertEqual($sectionObject->getColumnId(), $firstColumn['id']);
    $this->assertEqual($sectionObject->getIndex(), $firstColumn['index'] + 1);
    $this->assertEqual($sectionObject->getType(), 'part');

    // Try changing the section
    $sectionObject->setClass('my-class');
    $sectionObject->save();
    $reloadedSectionObject = DocumentSection::load($sectionId);
    $this->assertEqual($reloadedSectionObject->getClass(), 'my-class');

    // Try loading the part
    $part = TextPart::load($sectionObject->getPartId());
    $this->assertNotNull($part);

    // Add part by creating it first...
    $textPartCtrl = new TextPartController();
    $newPart = $textPartCtrl->createPart();
    $sectionFromPartId = DocumentTemplateEditor::addSectionFromPart($firstColumn['id'], $firstColumn['index'] + 2, $newPart);
    $this->assertTrue($sectionFromPartId > 0);

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

    // Check that we cannot remove first column
    $removeColumnSuccess = DocumentTemplateEditor::deleteColumn($firstColumn['id']);
    $this->assertFalse($removeColumnSuccess);

    TestService::removeTestPage($page);
    TestService::removeTestPage($other);
  }


  function testColumns() {
    $page = TestService::createTestPage();

    $structure = DocumentTemplateEditor::getStructure($page->getId());
    Log::debug($structure);

    $columnId = DocumentTemplateEditor::addColumn($structure[1]['id'], 1);
    $this->assertTrue($columnId > 0);

    $loadedColumn = DocumentColumn::load($columnId);
    $this->assertNotNull($loadedColumn);
    $this->assertEqual($page->getId(), $loadedColumn->getPageId());
    $this->assertEqual($structure[1]['id'], $loadedColumn->getRowId());

    // Try changing the column
    DocumentTemplateEditor::updateColumn($columnId, [
      'width' => '50%',
      'left' => '20pt',
      'right' => '10%',
      'top' => '5px',
      'bottom' => '10vh'
    ]);
    $loadedColumn = DocumentColumn::load($columnId);
    $this->assertEqual('50%', $loadedColumn->getWidth());

    $structure = DocumentTemplateEditor::getStructure($page->getId());
    $this->assertTrue(2, count($structure[1]['columns']));

    $firstColumn = $structure[1]['columns'][1];
    $this->assertEqual($columnId, $firstColumn['id']);

    $sectionId = DocumentTemplateEditor::addSection($columnId, 1, 'text');
    $loadedSection = DocumentSection::load($sectionId);
    $this->assertNotNull($loadedSection);
    $this->assertNotNull(TextPart::load($loadedSection->getPartId()));

    // Move the column to the right
    $this->assertTrue(DocumentTemplateEditor::moveColumn($columnId, 1));
    // Trying to move again should fail
    $this->assertFalse(DocumentTemplateEditor::moveColumn($columnId, 1));

    // The column should now have position 2
    $structure = DocumentTemplateEditor::getStructure($page->getId());
    $this->assertEqual($columnId, $structure[1]['columns'][2]['id']);

    // Add a new column at position 1
    $extraColumnId = DocumentTemplateEditor::addColumn($structure[1]['id'], 1);
    $this->assertTrue($extraColumnId > 0);

    // Reload the structure
    $structure = DocumentTemplateEditor::getStructure($page->getId());
    $this->assertEqual($extraColumnId, $structure[1]['columns'][1]['id']);
    $this->assertEqual($columnId, $structure[1]['columns'][3]['id']);

    // You should not be able to move the new column left
    $this->assertFalse(DocumentTemplateEditor::moveColumn($extraColumnId, -1));

    // Delete column and check that its contents are gone
    $this->assertTrue(DocumentTemplateEditor::deleteColumn($columnId));
    $this->assertNull(DocumentSection::load($sectionId));
    $this->assertNull(TextPart::load($loadedSection->getPartId()));

    TestService::removeTestPage($page);
  }

  function testRows() {
    $page = TestService::createTestPage();

    $rowId = DocumentTemplateEditor::addRow($page->getId(), 1);
    $this->assertTrue($rowId > 0);

    $structure = DocumentTemplateEditor::getStructure($page->getId());
    $this->assertEqual(2, count($structure));
    $this->assertEqual($rowId, $structure[1]['id']);

    // Check that a column was added
    $this->assertTrue($structure[1]['columns'][1]['id'] > 0);

    // Can move it down
    $this->assertTrue(DocumentTemplateEditor::moveRow($rowId, 1));

    // Cannot move any farther
    $this->assertFalse(DocumentTemplateEditor::moveRow($rowId, 1));

    // Can move it up again
    $this->assertTrue(DocumentTemplateEditor::moveRow($rowId, -1));

    $row = DocumentRow::load($rowId);

    $row->setTop('30px');
    $row->setBottom('20%');
    $row->setSpacing('20px');
    $row->setLayout('flex');
    $row->setClass('my-class');
    $row->setStyle('<if></if>');
    $row->save();

    $row = DocumentRow::load($rowId);
    $this->assertEqual($row->getTop(), '30px');
    $this->assertEqual($row->getBottom(), '20%');
    $this->assertEqual($row->getSpacing(), '20px');
    $this->assertEqual($row->getLayout(), 'flex');
    $this->assertEqual($row->getClass(), 'my-class');
    $this->assertEqual($row->getStyle(), '<if></if>');

    DocumentTemplateEditor::updateRow($rowId, [
      'top' => '',
      'bottom' => '10px',
      'spacing' => '5px',
      'class' => 'some-other-class'
    ]);

    $row = DocumentRow::load($rowId);
    $this->assertEqual($row->getTop(), '');
    $this->assertEqual($row->getBottom(), '10px');
    $this->assertEqual($row->getSpacing(), '5px');
    $this->assertEqual($row->getClass(), 'some-other-class');

    TestService::removeTestPage($page);
  }


  function testSections() {
    $page = TestService::createTestPage();

    $secondRowId = DocumentTemplateEditor::addRow($page->getId(), 2);
    DocumentTemplateEditor::addColumn($secondRowId, 2);

    $structure = DocumentTemplateEditor::getStructure($page->getId());
    $this->assertEqual(1,count($structure[1]['columns']));
    $this->assertEqual(2,count($structure[2]['columns']));

    $topColumnId = $structure[1]['columns'][1]['id'];
    $bottomLeftId = $structure[2]['columns'][1]['id'];
    $bottomRightId = $structure[2]['columns'][2]['id'];


    $sectionId = DocumentTemplateEditor::addSection($topColumnId, 1, 'text');
    $this->assertTrue($sectionId > 0);

    // Add 2 sections at start of top column
    DocumentTemplateEditor::addSection($topColumnId, 1, 'text');
    DocumentTemplateEditor::addSection($topColumnId, 1, 'text');

    // Add 2 sections at start of other columns
    DocumentTemplateEditor::addSection($bottomLeftId, 1, 'text');
    DocumentTemplateEditor::addSection($bottomLeftId, 1, 'text');

    DocumentTemplateEditor::addSection($bottomRightId, 1, 'text');
    DocumentTemplateEditor::addSection($bottomRightId, 1, 'text');

    $loadedSection = DocumentSection::load($sectionId);
    $this->assertEqual(3, $loadedSection->getIndex());

    // Cannot move section down since it is the last
    $this->assertFalse(DocumentTemplateEditor::moveSection($sectionId, 1));

    // Cannot move anyting but -1 or 1
    $this->assertFalse(DocumentTemplateEditor::moveSection($sectionId, 0));
    $this->assertFalse(DocumentTemplateEditor::moveSection($sectionId, 2));
    $this->assertFalse(DocumentTemplateEditor::moveSection($sectionId, -2));
    $this->assertFalse(DocumentTemplateEditor::moveSection($sectionId, null));
    $this->assertFalse(DocumentTemplateEditor::moveSection($sectionId, "up"));

    // Can move up
    $this->assertTrue(DocumentTemplateEditor::moveSection($sectionId, -1));

    // Now it should have position 2
    $loadedSection = DocumentSection::load($sectionId);
    $this->assertEqual(2, $loadedSection->getIndex());

    // Can move up again
    $this->assertTrue(DocumentTemplateEditor::moveSection($sectionId, -1));
    $loadedSection = DocumentSection::load($sectionId);
    $this->assertEqual(1, $loadedSection->getIndex());

    // Cannot move further up
    $this->assertFalse(DocumentTemplateEditor::moveSection($sectionId, -1));

    $didMove = DocumentTemplateEditor::moveSectionFar([
      'sectionId' => $sectionId,
      'rowIndex' => 1, // zero based
      'columnIndex' => 0, // zero based
      'sectionIndex' => 0 // zero based
    ]);
    $this->assertTrue($didMove);
    $loadedSection = DocumentSection::load($sectionId);
    $this->assertEqual($bottomLeftId, $loadedSection->getColumnId());
    $this->assertEqual(1, $loadedSection->getIndex());

    // Move it inside the same column
    $didMove = DocumentTemplateEditor::moveSectionFar([
      'sectionId' => $sectionId,
      'rowIndex' => 1,
      'columnIndex' => 0,
      'sectionIndex' => 2
    ]);
    $this->assertTrue($didMove);
    $loadedSection = DocumentSection::load($sectionId);
    $this->assertEqual($bottomLeftId, $loadedSection->getColumnId());
    $this->assertEqual(3, $loadedSection->getIndex());

    // Move it inside the same column
    $didMove = DocumentTemplateEditor::moveSectionFar([
      'sectionId' => $sectionId,
      'rowIndex' => 1,
      'columnIndex' => 0,
      'sectionIndex' => 0
    ]);
    $this->assertTrue($didMove);
    $loadedSection = DocumentSection::load($sectionId);
    $this->assertEqual($bottomLeftId, $loadedSection->getColumnId());
    $this->assertEqual(1, $loadedSection->getIndex());

    // Move it inside the same column
    $didMove = DocumentTemplateEditor::moveSectionFar([
      'sectionId' => $sectionId,
      'rowIndex' => 1,
      'columnIndex' => 0,
      'sectionIndex' => 1
    ]);
    $this->assertTrue($didMove);
    $loadedSection = DocumentSection::load($sectionId);
    $this->assertEqual($bottomLeftId, $loadedSection->getColumnId());
    $this->assertEqual(2, $loadedSection->getIndex());

    // Check that we cannot move to row index 2
    $this->assertFalse(DocumentTemplateEditor::moveSectionFar([
      'sectionId' => $sectionId,
      'rowIndex' => 2, // zero based
      'columnIndex' => 0, // zero based
      'sectionIndex' => 0 // zero based
    ]));

    // Check that we cannot move to column index 2 of second row
    $this->assertFalse(DocumentTemplateEditor::moveSectionFar([
      'sectionId' => $sectionId,
      'rowIndex' => 1, // zero based
      'columnIndex' => 2, // zero based
      'sectionIndex' => 0 // zero based
    ]));

    TestService::removeTestPage($page);
  }
}