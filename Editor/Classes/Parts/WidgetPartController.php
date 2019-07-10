<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class WidgetPartController extends PartController
{
  function __construct() {
    parent::__construct('widget');
  }

  function createPart() {
    $part = new WidgetPart();
    $part->save();
    return $part;
  }

  function isLiveEnabled() {
    return true;
  }

  function renderUsingDesign() {
    return true;
  }

  function display($part,$context) {
    return $this->render($part,$context);
  }

  function editor($part,$context) {
    return '<div id="part_widget_container">' . $this->render($part,$context) . '</div>' .
      $this->buildHiddenFields([
      'key' => $part->getKey(),
      'data' => $part->getData()
      ]) .
      $this->getEditorScript();
  }

  function getFromRequest($id) {
    $part = WidgetPart::load($id);
    $part->setKey(Request::getString('key'));
    $part->setData(Request::getString('data'));
    return $part;
  }

  function buildSub($part,$context) {
    $xml = '<widget xmlns="' . $this->getNamespace() . '" key="' . Strings::escapeXML($part->getKey()) . '">';
    if (DOMUtils::isValidFragment($part->getData())) {
      $xml .= $part->getData();
    }
    $xml .= '</widget>';
    return $xml;
  }

  function importSub($node,$part) {
    if ($widget = DOMUtils::getFirstChildElement($node,'widget')) {
      $part->setKey($widget->getAttribute('key'));
      $data = DOMUtils::getInnerXML($widget);
      $data = DOMUtils::stripNamespaces($data);
      $part->setData($data);
    }
  }


  function getToolbars() {
    return [
      UI::translate(['Widget', 'da' => 'Widget']) => ''
    ];
  }

  function getEditorUI($part,$context) {
    return '
    <window title="{Widget; da:Widget}" icon="common/info" name="widgetDataWindow" width="400" padding="5" closable="false">
      <formula name="widgetDataFormula">
        <fields labels="above">
          <field>
            <code-input key="data"/>
          </field>
        </fields>
      </formula>
    </window>
    ';
  }
}
?>