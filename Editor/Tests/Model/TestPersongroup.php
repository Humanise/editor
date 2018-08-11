<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class TestPersongroup extends AbstractObjectTest {

  function __construct() {
    parent::__construct('persongroup');
  }

  function testProperties() {
    $obj = new Persongroup();
    $obj->setTitle('My group');
    $obj->save();

    $obj2 = Persongroup::load($obj->getId());
    $this->assertEqual($obj2->getTitle(),'My group');

    $obj2->remove();
  }
}
?>