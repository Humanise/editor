<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Objects
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Workflow'] = [
  'table' => 'workflow',
  'properties' => [
    'recipe' => ['type' => 'string']
  ]
];

class Workflow extends ModelObject {
  var $recipe;

  function __construct() {
    parent::__construct('workflow');
  }

  static function load($id) {
    return ModelObject::get($id,'workflow');
  }

  function setRecipe($recipe) {
    $this->recipe = $recipe;
  }

  function getRecipe() {
    return $this->recipe;
  }

}