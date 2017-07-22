<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class CustomPartController extends PartController
{
  function CustomPartController() {
    parent::PartController('custom');
  }

  static function createPart() {
    $part = new CustomPart();
    $part->save();
    return $part;
  }

  function display($part,$context) {
    return $this->render($part,$context) . $this->resources($part);
  }

  private function resources($part) {
    $view = View::load($part->getViewId());
    $css = '';
    if ($view) {
      $path = $view->getPath();
      $cssFiles = ['inline.css', 'async.css'];
      foreach ($cssFiles as $file) {
        $fullPath = FileSystemService::join($path, $file);
        if (FileSystemService::canRead($fullPath)) {
          $url = ConfigurationService::getCachedUrl('../../../', $fullPath);
          $css .= '<link rel="stylesheet" type="text/css" href="' . $url . '"/>';
        }
      }
    } else {
      Log::debug('No view');
    }
    return $css;
  }

  function editor($part,$context) {

    return '<div id="part_custom_container">' . $this->render($part,$context) . '</div>' .
      $this->resources($part) .

    $this->buildHiddenFields([
      'workflowId' => $part->getWorkflowId(),
      'viewId' => $part->getViewId()
    ]) .
    '<script src="' . ConfigurationService::getBaseUrl() . 'Editor/Parts/custom/editor.js" type="text/javascript" charset="utf-8"></script>';
  }

  function getFromRequest($id) {
    $part = CustomPart::load($id);
    $part->setWorkflowId(Request::getInt('workflowId'));
    $part->setViewId(Request::getInt('viewId'));
    return $part;
  }

  function isDynamic($part) {
    return true;
  }

  function buildSub($part,$context) {
    $xml = '<custom xmlns="' . $this->getNamespace() . '">';
    $workflow = Workflow::load($part->getWorkflowId());
    $view = View::load($part->getViewId());
    if ($workflow && $view) {
      $parser = new WorkflowParser();
      $desc = $parser->parse($workflow->getRecipe());
      if ($desc) {
        $result = $desc->run();
        $path = $view->getPath();
        $twigTemplate = FileSystemService::join($path, 'template.twig');
        if (file_exists(FileSystemService::getFullPath($twigTemplate))) {
          $rendered = RenderingService::applyTwigTemplate([
            'path' => $twigTemplate,
            'variables' => ['data' => $result]
          ]);
        } else {
          // TODO: Output <error> instead
          $rendered = '<span>Template not found: ' . Strings::escapeXML($twigTemplate) . '</span>';
        }
        $inlineCSS = FileSystemService::join($path, 'inline.css');
        $asyncCSS = FileSystemService::join($path, 'async.css');
        $xml .= '<css xmlns="http://uri.in2isoft.com/onlinepublisher/resource/"';
        if (FileSystemService::canRead($inlineCSS)) {
          $xml .= ' inline="' . Strings::escapeXML($inlineCSS) . '"';
        }
        if (FileSystemService::canRead($asyncCSS)) {
          $xml .= ' async="' . Strings::escapeXML($asyncCSS) . '"';
        }
        $xml .= '/>';
        // TODO: optimize performance + maybe handle invalid XML
        if (!DomUtils::isValidFragment($rendered)) {
          // TODO: Output <error> instead
          $rendered = '';
        }
        $xml .= '<rendered xmlns="http://www.w3.org/1999/xhtml">' . $rendered . '</rendered>';
      }
    }
    $xml .= '</custom>';
    return $xml;
  }

  function importSub($node,$part) {
/*    if ($object = DOMUtils::getFirstDescendant($node,'object')) {
      if ($id = intval($object->getAttribute('id'))) {
        $part->setFileId($id);
      }
    }
    if ($text = DOMUtils::getFirstDescendant($node,'text')) {
      $part->setText(DOMUtils::getText($text));
    }*/
  }


  function getToolbars() {
    return [
      GuiUtils::getTranslated(['Custom', 'da' => 'Speciel']) => '
      <item label="{Workflow; da:Arbejdsgang}">
        <dropdown name="workflow" width="200">' . UI::buildOptions('workflow') . '</dropdown>
      </item>
      <item label="{View; da:Visning}">
        <dropdown name="view" width="200">' . UI::buildOptions('view') . '</dropdown>
      </item>
    '
    ];
  }



  function getEditorUI($part,$context) {
    return '
    <window title="{Add file; da:Tilføj fil}" name="customPartWindow" width="300" padding="10">
      <buttons align="center" top="10">
        <button name="cancelUpload" text="{Close; da:Luk}"/>
        <button name="upload" text="{Select file...; da:Vælg fil...}" highlighted="true"/>
      </buttons>
    </window>
    ';
  }
}
?>