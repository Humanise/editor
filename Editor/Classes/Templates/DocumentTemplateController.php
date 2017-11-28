<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Templates
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class DocumentTemplateController extends TemplateController
{
  function DocumentTemplateController() {
    parent::TemplateController('document');
  }

  function create($page) {
    $sql = "insert into document (page_id) values (@id)";
    Database::insert($sql, $page->getId());

    $sql = "insert into document_row (page_id,`index`) values (@id,1)";
    $rowId = Database::insert($sql, $page->getId());

    $sql = "insert into document_column (page_id,`index`,row_id) values (@int(pageId), 1, @int(rowId))";
    $columnId = Database::insert($sql, ['pageId' => $page->getId(), 'rowId' => $rowId]);
  }

  function delete($page) {
    $this->removeAll($page->getId());
    $sql = "delete from document where page_id = @id";
    Database::delete($sql, $page->getId());
  }

  function import($id,$doc) {
    // First clear the document
    $this->removeAll($id);

    $rows = DOMUtils::getChildElements($doc->documentElement,'row');
    $rowPosition = 0;
    foreach ($rows as $row) {
      $rowPosition++;

      $sql = [
        'table' => 'document_row',
        'values' => [
          'index' => ['int' => $rowPosition],
          'top' => ['text' => $row->getAttribute('top')],
          'bottom' => ['text' => $row->getAttribute('bottom')],
          'class' => ['text' => $row->getAttribute('class')],
          'page_id' => ['int' => $id]
        ]
      ];
      $rowId = Database::insert($sql);

      $columns = DOMUtils::getChildElements($row,'column');
      $columnPosition = 0;
      // Lopp through all columns
      foreach ($columns as $column) {
        $columnPosition++;

        $sql = [
          'table' => 'document_column',
          'values' => [
            'row_id' => ['int' => $rowId],
            'width' => ['text' => $column->getAttribute('width')],
            'left' => ['text' => $column->getAttribute('left')],
            'right' => ['text' => $column->getAttribute('right')],
            'top' => ['text' => $column->getAttribute('top')],
            'bottom' => ['text' => $column->getAttribute('bottom')],
            'class' => ['text' => $column->getAttribute('class')],
            'index' => ['int' => $columnPosition],
            'page_id' => ['int' => $id]
          ]
        ];
        $columnId = Database::insert($sql);

        $sectionPosition = 0;
        $sections = DOMUtils::getChildElements($column,'section');
        // Lopp through all sections
        foreach ($sections as $section) {
          if ($part = DOMUtils::getFirstChildElement($section,'part')) {
            $sectionPosition++;
            $type = $part->getAttribute('type');
            // Get a new Part objects

            if ($controller = PartService::getController($type)) {
              $obj = $controller->importFromNode($part);
              $obj->save();

              $sql = [
                'table' => 'document_section',
                'values' => [
                  'page_id' => ['int' => $id],
                  'column_id' => ['int' => $columnId],
                  'type' => ['text' => 'part'],
                  'part_id' => ['int' => $obj->getId()],
                  'index' => ['int' => $sectionPosition],
                  'width' => ['text' => $column->getAttribute('width')],
                  'float' => ['text' => $column->getAttribute('float')],
                  'class' => ['text' => $column->getAttribute('class')],
                  'top' => ['text' => $column->getAttribute('top')],
                  'bottom' => ['text' => $column->getAttribute('bottom')],
                  'left' => ['text' => $column->getAttribute('left')],
                  'right' => ['text' => $column->getAttribute('right')]
                ]
              ];
              Database::insert($sql);
            }
          }
        }
      }
    }
    }

  function removeAll($id) {
    $sql = "delete from document_row where page_id = @id";
    Database::delete($sql, $id);

    $sql = "delete from document_column where page_id = @id";
    Database::delete($sql, $id);

    $sql = "select part.id,part.type from document_section,part where part.id=document_section.part_id and document_section.page_id = @id";
    $result = Database::select($sql, $id);
    while ($row = Database::next($result)) {
      if ($part = PartService::load($row['type'], $row['id'])) {
        $part->remove();
      }
    }
    Database::free($result);

    $sql = "delete from document_section where page_id = @id";
    Database::delete($sql, $id);
  }

  function build($id) {
    $out = $this->getData($id);
    return ['data' => $out['xml'], 'dynamic' => $out['dynamic'], 'index' => $out['index']];
  }

  function getData($id) {
    $context = $this->buildPartContext($id);
    $dynamic = false;
    $index = '';
    $output = '<content xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/document/1.0/">';
    $sql = "select `id`, `top`, `bottom`, `spacing`, `class`, `style`,`layout` from document_row where page_id=@int(id) order by `index`";
    $result_row = Database::select($sql, ['id' => $id]);
    while ($row = Database::next($result_row)) {
      $output .= '<row id="' . $row['id'] . '"';
      foreach (['top', 'bottom', 'spacing', 'class', 'layout'] as $key) {
        if (!empty($row[$key])) {
          $output .= ' ' . $key . '="' . Strings::escapeXML($row[$key]) . '"';
        }
      }
      $output .= '>';
      $output .= DocumentTemplateController::getStyle($row['style']);
      $sql = "select `id`, `top`, `bottom`, `left`, `right`, `width`, `class`, `style` from document_column where row_id=@int(id) order by `index`";
      $result_col = Database::select($sql, ['id' => $row['id']]);
      while ($col = Database::next($result_col)) {
        $output .= '<column id="' . $col['id'] . '"';
        foreach (['width', 'left', 'right', 'top', 'bottom', 'class'] as $key) {
          if (!empty($col[$key])) {
            $output .= ' ' . $key . '="' . Strings::escapeXML($col[$key]) . '"';
          }
        }
        $output .= '>';
        $output .= DocumentTemplateController::getStyle($col['style']);
        $sql = "select document_section.*,part.type as part_type from document_section left join part on part.id = document_section.part_id where document_section.column_id=@int(id) order by document_section.`index`";
        $result_sec = Database::select($sql, ['id' => $col['id']]);
        while ($sec = Database::next($result_sec)) {

          $output .= '<section id="' . $sec['id'] . '"';
          foreach (['left', 'right', 'top', 'bottom', 'float', 'width', 'class'] as $key) {
            if (!empty($sec[$key])) {
              $output .= ' ' . $key . '="' . Strings::escapeXML($sec[$key]) . '"';
            }
          }
          $output .= '>';
          $output .= DocumentTemplateController::getStyle($sec['style']);
          $partArr = $this->partPublish($sec['type'],$sec['id'],$id,$sec['part_id'],$sec['part_type'],$context);
          $output .= $partArr['output'];
          $index .= ' ' . $partArr['index'] . "\n";
          if ($partArr['dynamic']) {
            $dynamic = true;
          }
          $output .= '</section>';
        }
        Database::free($result_sec);
        $output .= '</column>';
      }
      Database::free($result_col);
      $output .= '</row>';
    }
    Database::free($result_row);
    $output .= '</content>';
    return ['xml' => $output, 'index' => $index, 'dynamic' => $dynamic];
  }

  static function getStyle($xml) {
    if (Strings::isNotBlank($xml)) {
      if (DOMUtils::isValidFragment($xml)) {
        return '<style xmlns="http://uri.in2isoft.com/onlinepublisher/style/1.0/">' . $xml . '</style>';
      }
    }
    return '';
  }

  static function renderRowStyle($id, $styleXml) {
    $xml = '<content xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/document/1.0/">' .
      '<row id="' . $id . '">' . DocumentTemplateController::getStyle($styleXml) . '</row>' .
      '</content>';

    $result = RenderingService::renderFragment($xml);
    if (preg_match('/<style[^>]{0,}>(.*)<\/style>/uism', $result, $mathces)) {
      return $mathces[1];
    }
    return '';
  }

  static function buildPartContext($pageId) {
    $context = new PartContext();

    $sql = "SELECT design.unique as design, lower(page.language) as language from page,design where design.object_id = page.design_id and page.id = @int(id)";
    if ($result = Database::selectFirst($sql, ['id' => $pageId])) {
      $context->setLanguage($result['language']);
      $context->setDesign($result['design']);
    }

    $sql = "SELECT link.*,page.path from link left join page on page.id=link.target_id and link.target_type='page' where page_id=@int(id) and source_type='text'";
    $result = Database::select($sql, ['id' => $pageId]);
    while ($row = Database::next($result)) {
      $context -> addBuildLink(
        Strings::escapeSimpleXML($row['source_text']),
        $row['target_type'],
        $row['target_id'],
        $row['target_value'],
        $row['target'],
        $row['alternative'],
        $row['path'],
        $row['id'],
        intval($row['part_id'])
      );
    }
    Database::free($result);

    return $context;
  }

  function partPublish($type,$id,$pageId,$partId,$partType,$context) {
    $output = '';
    $index = '';
    $dynamic = false;

    $ctrl = PartService::getController($partType);
    if ($ctrl) {
      $part = PartService::load($partType,$partId);
      $dynamic = $ctrl->isDynamic($part);
      if ($dynamic) {
        $output = "<!-- dynamic:part#" . $partId . " -->";
      } else {
        $output = $ctrl->build($part,$context);
      }
      $index = $ctrl->getIndex($part);
    }
    return ['output' => $output, 'index' => $index, 'dynamic' => $dynamic];
  }

  function dynamic($id,&$state) {
    $context = new PartContext();
    $sql = "select page_id,part_id,part.type,part.dynamic from document_section,part where page_id = @id and document_section.part_id=part.id and part.dynamic=1";
    $result = Database::select($sql, $id);
    while ($row = Database::next($result)) {
      $ctrl = PartService::getController($row['type']);
      if ($ctrl) {
        $part = PartService::load($row['type'],$row['part_id']);
        $partData = $ctrl->build($part,$context);
      }
      $state['data'] = str_replace('<!-- dynamic:part#' . $row['part_id'] . ' -->', $partData, $state['data']);
    }
    Database::free($result);
  }




}