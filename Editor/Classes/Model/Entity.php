<?php
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class Entity {

  var $id;

  static $schema = [];

  function setId($id) {
    $this->id = $id;
  }

  function getId() {
    return $this->id;
  }

  function set($property, $value) {
    if (property_exists(get_class($this), $property)) {
      $this->$property = $value;
    }
  }
}