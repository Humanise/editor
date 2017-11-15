<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Productoffer'] = [
  'table' => 'productoffer',
  'properties' => [
      'offer' => ['type' => 'string'],
      'productId' => ['type' => 'int', 'column' => 'product_id'],
      'personId' => ['type' => 'int', 'column' => 'person_id'],
      'expiry' => ['type' => 'datetime']
    ]
];

class Productoffer extends Object {
  var $offer;
  var $productId = 0;
  var $personId = 0;
  var $expiry;

  function Productoffer() {
    parent::Object('productoffer');
  }

  static function load($id) {
    return Object::get($id,'productoffer');
  }

  function updateTitle() {
    $this->title = $this->offer;
  }

  function setOffer($offer) {
    $this->offer = $offer;
    $this->updateTitle();
  }

  function getOffer() {
    return $this->offer;
  }

  function setProductId($id) {
    $this->productId = $id;
  }

  function getProductId() {
    return $this->productId;
  }

  function setPersonId($personId) {
      $this->personId = $personId;
  }

  function getPersonId() {
      return $this->personId;
  }

  function setExpiry($expiry) {
      $this->expiry = $expiry;
  }

  function getExpiry() {
      return $this->expiry;
  }

  function find($query = []) {
    $parts = [];
    $parts['columns'] = 'object.id';
    $parts['tables'] = 'productoffer,object,object as product,object as person';
    $parts['limits'] = 'productoffer.object_id=object.id and productoffer.product_id=product.id and productoffer.person_id=person.id';
    $parts['ordering'] = '';

    if ($query['sort'] == 'offer') {
      $parts['ordering'] = "productoffer.offer";
    } else if ($query['sort'] == 'product') {
      $parts['ordering'] = "product.title";
    } else if ($query['sort'] == 'person') {
      $parts['ordering'] = "person.title";
    } else if ($query['sort'] == 'expiry') {
      $parts['ordering'] = "productoffer.expiry";
    }
    if ($query['direction'] == 'descending') {
      $parts['ordering'] .= ' desc';
    } else {
      $parts['ordering'] .= ' asc';
    }

    $list = ObjectService::_find($parts,$query);
    $list['result'] = [];
    foreach ($list['rows'] as $row) {
      $list['result'][] = Productoffer::load($row['id']);
    }
    return $list;
  }

  /////////////////////////// GUI /////////////////////////

  function getIcon() {
    return "common/object";
  }
}
?>