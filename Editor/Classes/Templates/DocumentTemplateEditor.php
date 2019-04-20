<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Templates
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class DocumentTemplateEditor
{
  static function deleteRow($rowId) {
    $sql = "select * from document_row where id = @id";
    $row = Database::selectFirst($sql, $rowId);
    if (!$row) {
      Log::debug('Row not found');
      return false;
    }
    $index = $row['index'];
    $pageId = $row['page_id'];

    $sql = "select count(id) as num from document_row where page_id = @int(id)";
    $row = Database::selectFirst($sql, ['id' => $pageId]);
    if ($row['num'] < 2) {
      // Cannot delete the last row
      return false;
    }

    $latestRow = 0;

    $sql = "select * from document_row where page_id = @int(pageId) and `index` > @int(index)";
    $result = Database::select($sql, ['pageId' => $pageId, 'index' => $index]);
    while ($row = Database::next($result)) {
      $sql = "update document_row set `index` = @int(index) where id = @int(id)";
      Database::update($sql, ['id' => $row['id'], 'index' => ($row['index'] - 1)]);
      $latestRow = $row['id'];
    }
    Database::free($result);

    $sql = "select document_section.*,part.type as part_type from document_section left join part on part.id=document_section.part_id left join document_column on document_section.column_id=document_column.id where document_column.row_id = @int(id)";
    $result = Database::select($sql, ['id' => $rowId]);
    while ($row = Database::next($result)) {
      $type = $row['type'];
      $sectionId = $row['id'];
      $partId = $row['part_id'];
      $partType = $row['part_type'];
      if ($type == 'part') {
        if ($part = Part::get($partType,$partId)) {
          $part->remove();
        }
      }
      $sql = "delete from document_section where id = @int(id)";
      Database::delete($sql, ['id' => $sectionId]);
    }
    Database::free($result);

    $sql = "delete from document_column where row_id = @int(id)";
    Database::delete($sql, ['id' => $rowId]);

    $sql = "delete from document_row where id = @int(id)";
    Database::delete($sql, ['id' => $rowId]);

    DocumentTemplateEditor::fixStructure($pageId);
    PageService::markChanged($pageId);
    return true;
  }

  static function addPartAtEnd($pageId, $part) {
    if (!$part->isPersistent()) {
      Log::debug('The part is not persistent!');
      return null;
    }
    $sql = "select id from document_row where page_id = @id order by `index` desc";
    if ($row = Database::selectFirst($sql, $pageId)) {
      $rowId = $row['id'];
      $sql = "select id from document_column where row_id = @id order by `index` desc";
      if ($row = Database::selectFirst($sql, $rowId)) {
        $columnId = $row['id'];
        $sql = "select max(`index`) as `index` from document_section where column_id = @id";
        $index = 1;
        if ($row = Database::selectFirst($sql, $columnId)) {
          $index = $row['index'] + 1;
        }
        return DocumentTemplateEditor::addSectionFromPart($columnId, $index, $part);
      } else {
        Log::debug('No column found for first row of page=' . $pageId);
      }
    } else {
      Log::debug('No rows found for page=' . $pageId);
    }
    return null;
  }

  /**
   * @return The id of the section
   */
  static function addSection($columnId, $index, $partType) {
    $ctrl = PartService::getController($partType);
    if (!$ctrl) {
      Log::debug('Controller not found');
      return null;
    }
    if ($part = $ctrl->createPart()) {
      return DocumentTemplateEditor::addSectionFromPart($columnId, $index, $part);
    }
    return null;
  }

  /**
   * @return The id of the section
   */
  static function addSectionFromPart($columnId, $index, $part) {
    if (!$part->isPersistent()) {
      Log::debug('The part is not persistent!');
      return null;
    }
    $sql = "select page_id from document_column where id = @id";
    if ($row = Database::selectFirst($sql, $columnId)) {
      $pageId = $row['page_id'];
    } else {
      Log::debug('Column not found');
      return null;
    }

    // TODO Verify or sanitize index (> 1 etc.)

    $sql = "select * from document_section where column_id = @int(columnId) and `index` >= @int(index)";
    $result = Database::select($sql, ['columnId' => $columnId, 'index' => $index]);
    while ($row = Database::next($result)) {
      $sql = "update document_section set `index` = @int(index) where id = @int(id)";
      Database::update($sql, ['index' => $row['index'] + 1, 'id' => $row['id']]);
    }
    Database::free($result);

    $section = new DocumentSection();
    $section->setPageId($pageId);
    $section->setColumnId($columnId);
    $section->setIndex($index);
    $section->setType('part');
    $section->setPartId($part->getId());
    $section->save();
    $sectionId = $section->getId();

    DocumentTemplateEditor::fixStructure($pageId);
    PageService::markChanged($pageId);

    return $sectionId;
  }

  static function deleteColumn($columnId) {
    $sql = "select * from document_column where id = @id";
    $row = Database::selectFirst($sql, $columnId);
    if (!$row) {
      return false;
      Log::debug('Column with id=' . $columnId . ' not found!');
    }
    $index = $row['index'];
    $rowId = $row['row_id'];
    $pageId = $row['page_id'];

    $sql = "select count(id) as num from document_column where row_id = @id";
    $c = Database::selectFirst($sql, $rowId);
    if (!$c) {
      Log::debug('No columns found in row');
      return false;
    }
    if ($c['num'] < 2) {
      Log::debug('Aborting delete of last column');
      return false;
    }

    $sql = "select * from document_column where row_id = @int(rowId) and `index` > @int(index)";
    $result = Database::select($sql, ['rowId' => $rowId, 'index' => $index]);
    while ($row = Database::next($result)) {
      $sql = "update document_column set `index` = @int(index) where id = @id";
      Database::update($sql, ['index' => $row['index'] - 1, 'id' => $row['id']]);
    }
    Database::free($result);

    $sql = "select document_section.*,part.type as part_type from document_section left join part on part.id=document_section.part_id where column_id = @id";
    $result = Database::select($sql, $columnId);
    while ($row = Database::next($result)) {
      $type = $row['type'];
      $sectionId = $row['id'];
      $partType = $row['part_type'];
      $partId = $row['part_id'];
      if ($type == 'part') {
        if ($part = Part::get($partType,$partId)) {
          $part->remove();
        }
      }
    }
    Database::free($result);

    $sql = "delete from document_section where column_id = @id";
    Database::delete($sql, $columnId);

    $sql = "delete from document_column where id = @id";
    Database::delete($sql, $columnId);

    DocumentTemplateEditor::fixStructure($pageId);
    PageService::markChanged($pageId);
    return true;
  }

  static function getSection($sectionId) {
    $sql = "select document_section.*,part.type as part_type from document_section left join part on part.id = document_section.part_id where document_section.id = @id";
    return Database::selectFirst($sql, $sectionId);
  }

  static function deleteSection($sectionId) {

    $row = DocumentTemplateEditor::getSection($sectionId);
    if (!$row) {
      Log::debug('Unable to find section with id=' . $sectionId);
      return;
    }
    $index = $row['index'];
    $type = $row['type'];
    $columnId = $row['column_id'];
    $pageId = $row['page_id'];
    $partType = $row['part_type'];
    $partId = $row['part_id'];

    $sql = "select * from document_section where column_id = @id and `index` > @int(index)";
    $result = Database::select($sql, ['id' => $columnId, 'index' => $index]);
    while ($row = Database::next($result)) {
      $sql = "update document_section set `index` = @int(index) where id = @id";
      Database::update($sql, ['id' => $row['id'], 'index' => $row['index'] - 1]);
    }
    Database::free($result);

    $sql = "delete from document_section where id = @id";
    Database::delete($sql, $sectionId);

    DocumentTemplateEditor::fixStructure($pageId);
    PageService::markChanged($pageId);

    if ($type == 'part') {
      if ($part = Part::get($partType,$partId)) {
        $part->remove();
      }
    }
  }

  static function addRow($pageId, $index) {
    if (!PageService::exists($pageId)) {
      Log::debug('The page with id=' . $pageId . ' does not exist');
      return;
    }

    $sql = "select * from document_row where page_id = @int(pageId) and `index` >= @int(index)";
    $result = Database::select($sql, ['pageId' => $pageId, 'index' => $index]);
    while ($row = Database::next($result)) {
      $sql = "update document_row set `index` = @int(index) where id = @id";
      Database::update($sql, ['index' => $row['index'] + 1, 'id' => $row['id']]);
    }
    Database::free($result);

    $row = new DocumentRow();
    $row->setPageId($pageId);
    $row->setIndex($index);
    $row->save();

    $column = new DocumentColumn();
    $column->setPageId($pageId);
    $column->setRowId($row->getId());
    $column->setIndex(1);
    $column->save();

    DocumentTemplateEditor::fixStructure($pageId);
    PageService::markChanged($pageId);

    return $row->getId();
  }

  static function addColumn($rowId, $index) {
    $sql = "select * from document_row where id = @id";
    $row = Database::selectFirst($sql, $rowId);
    if (!$row) {
      Log::debug('Row not found');
      return;
    }
    $pageId = $row['page_id'];

    $sql = "select * from document_column where row_id = @int(rowId) and `index` >= @int(index)";
    $result = Database::select($sql, ['rowId' => $rowId, 'index' => $index]);
    while ($row = Database::next($result)) {
      $sql = "update document_column set `index` = @int(index) where id = @int(rowId)";
      Database::update($sql, ['rowId' => $row['id'], 'index' => $row['index'] + 1]);
    }
    Database::free($result);

    $sql = "insert into document_column (page_id,row_id,`index`,width) values (@int(pageId), @int(rowId), @int(index),'')";
    $columnId = Database::insert($sql, ['pageId' => $pageId, 'rowId' => $rowId, 'index' => $index]);

    DocumentTemplateEditor::fixStructure($pageId);
    PageService::markChanged($pageId);

    return $columnId;
  }

  static function moveSection($sectionId,$direction) {
    if ($direction !== 1 && $direction !== -1) {
      return false;
    }
    $section = DocumentSection::load($sectionId);
    if ($section && DocumentTemplateEditor::fixStructure($section->getPageId())) {
      $section = DocumentSection::load($sectionId);
    }
    if (!$section) {
      return false;
    }

    $index = $section->getIndex();
    $newIndex = $index + $direction;

    $sql = "select id from document_section where `index` = @int(index) and column_id = @int(column)";
    $otherResult = Database::selectFirst($sql, ['index' => $newIndex, 'column' => $section->getColumnId()]);

    if ($otherResult) {
      if ($other = DocumentSection::load($otherResult['id'])) {
        $other->setIndex($index);
        $other->save();

        $section->setIndex($newIndex);
        $section->save();

        PageService::markChanged($section->getPageId());
        return true;
      }
    }
    return false;
  }


  /**
   * The indices are Zero-based!
   * @param $params Example: array('sectionId' => «id»,'rowIndex' => «int»,'columnIndex' => «int»,'sectionIndex' => «int»)
   */
  static function moveSectionFar($params) {
    // TODO: NOTICE!!! ZERO BASED!!!
    Log::debug($params);
    $section = DocumentSection::load($params['sectionId']);
    if (!$section) {
      Log::debug('The section with id=' . $params['sectionId'] . ' could not be found');
      return false;
    }
    $pageId = $section->getPageId();
    $sectionId = $section->getId();

    DocumentTemplateEditor::fixStructure($pageId);

    $sql = "select id from document_row where page_id = @int(pageId) and `index` = @int(index)";
    $row = Database::selectFirst($sql, ['pageId' => $pageId, 'index' => $params['rowIndex'] + 1]);
    if (!$row) {
      Log::debug('Row not found: pageId=' . $pageId . ', rowIndex=' . $params['rowIndex']);
      return;
    }

    $sql = "select id from document_column where row_id = @int(rowId) and `index` = @int(index) order by `index`";
    $column = Database::selectFirst($sql, ['rowId' => $row['id'], 'index' => $params['columnIndex'] + 1]);

    if (!$column) {
      Log::debug('Column not found: pageId=' . $pageId . ', rowIndex=' . $params['rowIndex'] . ', columnIndex=' . $params['columnIndex']);
      return false;
    }
    $newColumnId = $column['id'];
    $newIndex = $params['sectionIndex'] + 1;

    $sql = "select id,`index` from document_section where column_id = @id order by `index`";
    $sections = Database::selectAll($sql, $newColumnId);
    $index = 0;
    foreach ($sections as $sec) {
      $index++;
      if ($sec['id'] == $sectionId) {
        $index--;
        continue;
      }
      if ($index == $newIndex) {
        $index++;
      }
      $sql = "update document_section set `index` = @int(index) where id = @id";
      Database::update($sql, ['id' => $sec['id'], 'index' => $index]);
    }

    $oldColumnId = $section->getColumnId();

    $section->setIndex($newIndex);
    $section->setColumnId($newColumnId);
    $section->save();

    DocumentTemplateEditor::_rebuildColumn($newColumnId);
    if ($oldColumnId != $newColumnId) {
      DocumentTemplateEditor::_rebuildColumn($oldColumnId);
    }

    PageService::markChanged($pageId);
    return true;
  }

  static function _rebuildColumn($id) {
    $sql = "select id,`index` from document_section where column_id = @id order by `index`";
    $sections = Database::selectAll($sql, $id);
    Log::debug($sections);
    for ($i = 0; $i < count($sections); $i++) {
      $sql = "update document_section set `index` = @int(index) where id = @id";
      Database::update($sql, ['index' => $i + 1, 'id' => $sections[$i]['id']]);
    }
  }


  static function moveRow($rowId, $direction) {
    if ($direction !== 1 && $direction !== -1) {
      return false;
    }
    $row = DocumentRow::load($rowId);
    if ($row && DocumentTemplateEditor::fixStructure($row->getPageId())) {
      $row = DocumentRow::load($rowId);
    }
    if (!$row) {
      Log::debug('Row not found id = ' . $rowId);
      return false;
    }

    $index = $row->getIndex();
    $newIndex = $index + $direction;

    $sql = "select * from document_row where `index` = @int(index) and page_id = @int(pageId)";
    $otherRow = Database::selectFirst($sql, ['index' => $newIndex, 'pageId' => $row->getPageId()]);
    if ($otherRow) {
      $row->setIndex($newIndex);
      $row->save();
      $sql = "update document_row set `index` = @int(index) where id = @id";
      Database::update($sql, ['index' => $index, 'id' => $otherRow['id']]);

      PageService::markChanged($row->getPageId());
      return true;
    }
  }

  static function moveColumn($columnId, $direction) {
    if ($direction !== 1 && $direction !== -1) {
      return false;
    }
    $sql = "select `index`,row_id,page_id from document_column where id = @id";
    $row = Database::selectFirst($sql, $columnId);
    if ($row && DocumentTemplateEditor::fixStructure($row['page_id'])) {
      $row = Database::selectFirst($sql);
    }
    if (!$row) {
      return false;
    }
    $index = $row['index'];
    $newIndex = $index + $direction;
    $rowId = $row['row_id'];

    $sql = "select * from document_column where `index` = @int(newIndex) and row_id = @id";
    $otherColumn = Database::selectFirst($sql, ['newIndex' => $newIndex, 'id' => $rowId]);
    if ($otherColumn) {
      $sql = "update document_column set `index` = @int(index) where id = @id";
      Database::update($sql, ['index' => $newIndex, 'id' => $columnId]);
      $sql = "update document_column set `index` = @int(index) where id = @id";
      Database::update($sql, ['index' => $index, 'id' => $otherColumn['id']]);
      PageService::markChanged($row['page_id']);
      return true;
    }
    return false;
  }

  static function updateColumn($id, $properties) {
    if ($column = DocumentColumn::load($id)) {
      foreach ($properties as $property => $value) {
        $column->set($property, $properties[$property]);
      }
      $column->save();
      PageService::markChanged($column->getPageId());
      return true;
    }
    return false;
  }

  static function updateRow($id, $properties) {

    if ($row = DocumentRow::load($id)) {
      foreach ($properties as $property => $value) {
        $row->set($property, $properties[$property]);
      }
      $row->save();
      PageService::markChanged($row->getPageId());
      return true;
    }
    return false;
  }

  /**
   * The purpose of this is to fix any problems with the structure.
   * The structure can get corrupt at any point (ie. in the past) so we need to fix it often.
   * @return {boolean} TRUE if the structure was modified
   */
  static function fixStructure($pageId) {
    $modified = false;
    $structure = DocumentTemplateEditor::getStructure($pageId);
    if (!$structure) {
      return false;
    }
    $rowIndex = 1;
    foreach ($structure as $row) {
      if ($row['index'] !== $rowIndex) {
        Log::debug('Row with id=' . $row['id'] . ' has index=' . $row['index'] . ' but should be index=' . $rowIndex);
        $sql = "update document_row set `index`=@int(index) where id=@int(id)";
        Database::update($sql,['id' => $row['id'], 'index' => $rowIndex]);
        $modified = true;
      }
      $columnIndex = 1;
      foreach ($row['columns'] as $column) {
        if ($column['index'] !== $columnIndex) {
          Log::debug('Column with id=' . $column['id'] . ' has index=' . $column['index'] . ' but should be index=' . $columnIndex);
          $sql = "update document_column set `index`=@int(index) where id=@int(id)";
          Database::update($sql,['id' => $column['id'], 'index' => $columnIndex]);
          $modified = true;
        }

        $sectionIndex = 1;
        foreach ($column['sections'] as $section) {
          if ($section['index'] !== $sectionIndex) {
            Log::debug('Section with id=' . $section['id'] . ' has index=' . $section['index'] . ' but should be index=' . $sectionIndex);
            $sql = "update document_section set `index`=@int(index) where id=@int(id)";
            Database::update($sql,['id' => $section['id'], 'index' => $sectionIndex]);
            $modified = true;
          }
          $sectionIndex++;
        }

        $columnIndex++;
      }
      $rowIndex++;
    }
    return $modified;
  }

  /** TODO: refactor using shared code with fixStructure */
  static function check($pageId) {
    $problems = [];
    $structure = DocumentTemplateEditor::getStructure($pageId);
    if (!$structure) {
      return;
    }
    $rowIndex = 1;
    foreach ($structure as $row) {
      if ($row['index'] !== $rowIndex) {
        $problems[] = [
          'description' => 'Row with id=' . $row['id'] . ' has index=' . $row['index'] . ' but should be index=' . $rowIndex
        ];
      }
      $columnIndex = 1;
      foreach ($row['columns'] as $column) {
        if ($column['index'] !== $columnIndex) {
          $problems[] = 'Column with id=' . $column['id'] . ' has index=' . $column['index'] . ' but should be index=' . $columnIndex;
        }

        $sectionIndex = 1;
        foreach ($column['sections'] as $section) {
          if ($section['index'] !== $sectionIndex) {
            $problems[] = 'Section with id=' . $section['id'] . ' has index=' . $section['index'] . ' but should be index=' . $sectionIndex;
          }
          $sectionIndex++;
        }

        $columnIndex++;
      }
      $rowIndex++;
    }
    return $problems;
  }

  static function getStructure($id) {
    $sql = "select
      document_row.id as row_id,
      document_row.index as row_index,
      document_row.top as row_top,
      document_row.bottom as row_bottom,
      document_row.spacing as row_spacing,
      document_row.class as row_class,

      document_column.id as column_id,
      document_column.index as column_index,
      document_column.top as column_top,
      document_column.bottom as column_bottom,
      document_column.left as column_left,
      document_column.right as column_right,
      document_column.width as column_width,

      document_section.id as section_id,
      document_section.index as section_index,
      document_section.type as section_type,
      document_section.top as section_top,
      document_section.bottom as section_bottom,
      document_section.left as section_left,
      document_section.right as section_right,
      document_section.width as section_width,
      document_section.float as section_float,
      document_section.class as section_class,
      document_section.part_id as part_id,
      part.type as part_type

      from document_row

      left join document_column on document_row.id = row_id
      left join document_section on document_column.id = column_id
      left join part on document_section.part_id = part.id

      where document_row.page_id = @int(id)

      order by document_row.index,document_row.id, document_column.index, document_column.id, document_section.index, document_section.id";
    //Log::debug($sql);
    $structure = [];

    $result = Database::select($sql, ['id' => $id]);
    while ($line = Database::next($result)) {
      $row = null;
      $column = null;
      $rowIndex = intval($line['row_index']);
      $columnIndex = intval($line['column_index']);
      $sectionIndex = intval($line['section_index']);

      if (!isset($structure[$rowIndex])) {
        $structure[$rowIndex] = [
          'id' => intval($line['row_id']),
          'index' => $rowIndex,
          'top' => $line['row_top'],
          'bottom' => $line['row_bottom'],
          'spacing' => $line['row_spacing'],
          'class' => $line['row_class'],
          'columns' => []
        ];
      }

      if (!isset($structure[$rowIndex]['columns'][$columnIndex])) {
        $structure[$rowIndex]['columns'][$columnIndex] = [
          'id' => $line['column_id'],
          'index' => $columnIndex,
          'width' => $line['column_width'],
          'top' => $line['column_top'],
          'bottom' => $line['column_bottom'],
          'left' => $line['column_left'],
          'right' => $line['column_right'],
          'sections' => []
        ];
      }
      if ($line['section_id']) {
        if (!isset($structure[$rowIndex]['columns'][$columnIndex]['sections'][$sectionIndex])) {
          $structure[$rowIndex]['columns'][$columnIndex]['sections'][$sectionIndex] = [
            'id' => $line['section_id'],
            'index' => $sectionIndex,
            'width' => $line['section_width'],
            'float' => $line['section_float'],
            'top' => $line['section_top'],
            'bottom' => $line['section_bottom'],
            'left' => $line['section_left'],
            'right' => $line['section_right'],
            'class' => $line['section_class'],
            'partType' => $line['part_type'],
            'partId' => $line['part_id']
          ];
        }
      }
    }
    Database::free($result);

    return $structure;
  }
}