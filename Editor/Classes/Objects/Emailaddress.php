<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Emailaddress'] = [
    'table' => 'emailaddress',
    'properties' => [
      'address' => ['type' => 'string'],
      'containingObjectId' => ['type' => 'int', 'column' => 'containing_object_id']
    ]
];

class Emailaddress extends ModelObject {
  var $address;
  var $containingObjectId = 0;

  function __construct() {
    parent::__construct('emailaddress');
  }

  static function load($id) {
    return ModelObject::get($id,'emailaddress');
  }

  function setAddress($address) {
    $this->title = $address;
    $this->address = $address;
  }

  function getAddress() {
    return $this->address;
  }

  function setContainingObjectId($id) {
    $this->containingObjectId = $id;
  }

  function getContainingObjectId() {
    return $this->containingObjectId;
  }

    /////////////////////////// Persistence ////////////////////////

  function sub_publish() {
    $data =
    '<emailaddress xmlns="' . parent::_buildnamespace('1.0') . '">' .
    '</emailaddress>';
    return $data;
  }

  /////////////////////////// GUI /////////////////////////

  function getIcon() {
    return "common/email";
  }
}
?>