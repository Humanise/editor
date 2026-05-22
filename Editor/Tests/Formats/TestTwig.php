<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Formats
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class TestTwig extends UnitTestCase {

  function testSimple() {

    $loader = new \Twig\Loader\ArrayLoader([
        'index' => 'Hello {{ person.firstname }} {{ person.surname }}!'
    ]);
    $twig = new \Twig\Environment($loader);

    $person = new Person();
    $person->setFirstname('John');
    $person->setSurname('Lennon');
    $result = $twig->render('index', ['person' => $person]);
    $this->assertEqual('Hello John Lennon!',$result);
  }
}