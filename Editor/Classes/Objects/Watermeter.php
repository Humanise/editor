<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Watermeter'] = [
  'table' => 'watermeter',
  'properties' => [
      'number' => ['type' => 'string']
  ]
];

class Watermeter extends ModelObject {
  var $number;

  function __construct() {
    parent::__construct('watermeter');
  }

  static function load($id) {
    return ModelObject::get($id,'watermeter');
  }

  function getIcon() {
    return "common/gauge";
  }

  function setNumber($number) {
    $this->number = $number;
    $this->setTitle($number);
  }

  function getNumber() {
    return $this->number;
  }

  function sub_index() {
    $address = Query::after('address')->withRelationFrom($this)->first();
    if ($address) {
      return $address->getIndex();
    }
  }
}
?>