<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class TestPerson extends AbstractObjectTest {

  function __construct() {
    parent::__construct('person');
  }

  function testProperties() {
    $obj = new Person();
    $obj->setFirstname('Jonas');
    $obj->setMiddlename('Brinkmann');
    $obj->setSurname('Munk');
    $obj->setInitials('jbm');
    $obj->setNickname('spunk');
    $obj->setJobtitle('Software developer');
    $obj->setSex(1);
    $obj->setStreetname('H/f Sundbyvester');
    $obj->setZipcode('2300');
    $obj->setCity('Copenhagen S');
    $obj->setCountry('dk');
    $obj->setWebaddress('http://www.jonasmunk.dk/');
    $obj->setImageId(10);

    $this->assertEqual($obj->getTitle(),'Jonas Brinkmann Munk');
    $obj->save();

    $loaded = Person::load($obj->getId());
    $this->assertEqual($loaded->getTitle(),$loaded->getTitle());
    $this->assertEqual($loaded->getFirstname(),$loaded->getFirstname());
    $this->assertEqual($loaded->getMiddlename(),$loaded->getMiddlename());
    $this->assertEqual($loaded->getSurname(),$loaded->getSurname());
    $this->assertEqual($loaded->getInitials(),$loaded->getInitials());
    $this->assertEqual($loaded->getNickname(),$loaded->getNickname());
    $this->assertEqual($loaded->getJobtitle(),$loaded->getJobtitle());
    $this->assertEqual($loaded->getSex(),$loaded->getSex());
    $this->assertEqual($loaded->getStreetname(),$loaded->getStreetname());
    $this->assertEqual($loaded->getZipcode(),$loaded->getZipcode());
    $this->assertEqual($loaded->getCity(),$loaded->getCity());
    $this->assertEqual($loaded->getCountry(),$loaded->getCountry());
    $this->assertEqual($loaded->getWebaddress(),$loaded->getWebaddress());
    $this->assertEqual($loaded->getImageId(),$loaded->getImageId());

    $loaded->remove();
  }

  function testRelations() {
    $person = new Person();
    $person->setFirstname('John');
    $person->save();

    $list = new Mailinglist();
    $list->save();
    $person->updateMailinglistIds([$list->getId()]);

    $listIds = $person->getMailinglistIds();
    $this->assertEqual(1, count($listIds));

    $list->remove();

    $listIds = $person->getMailinglistIds();
    $this->assertEqual(0, count($listIds));

    $group = new Persongroup();
    $group->save();
    $person->updateGroupIds([$group->getId()]);

    $groupIds = $person->getGroupIds();
    $this->assertEqual(1, count($groupIds));

    $group2 = new Persongroup();
    $group2->save();
    $person->addGroupId($group2->getId());

    $this->assertEqual(2, count($person->getGroupIds()));

    $group->remove();
    $group2->remove();

    $groupIds = $person->getGroupIds();
    $this->assertEqual(0, count($groupIds));

    $person->remove();
  }
}
?>