<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Pageblueprint'] = [
    'table' => 'pageblueprint',
    'properties' => [
      'designId' => ['type' => 'int', 'column' => 'design_id'],
      'frameId' => ['type' => 'int', 'column' => 'frame_id'],
      'templateId' => ['type' => 'int', 'column' => 'template_id'],
    ]
];

class Pageblueprint extends ModelObject {
  var $designId;
  var $frameId;
  var $templateId;

  function __construct() {
    parent::__construct('pageblueprint');
  }

  static function load($id) {
    return ModelObject::get($id,'pageblueprint');
  }

  function setDesignId($designId) {
      $this->designId = $designId;
  }

  function getDesignId() {
      return $this->designId;
  }

  function setFrameId($frameId) {
      $this->frameId = $frameId;
  }

  function getFrameId() {
      return $this->frameId;
  }

  function setTemplateId($templateId) {
      $this->templateId = $templateId;
  }

  function getTemplateId() {
      return $this->templateId;
  }
}
?>