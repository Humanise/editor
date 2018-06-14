<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Mailinglist'] = [
    'table' => 'mailinglist',
    'properties' => []
];

class Mailinglist extends Object {

  function Mailinglist() {
    parent::__construct('mailinglist');
  }

  function getIcon() {
    return 'common/email';
  }

  static function load($id) {
    return Object::get($id,'mailinglist');
  }

  function removeMore() {
    $sql = "delete from person_mailinglist where mailinglist_id = @int(id)";
    Database::delete($sql, ['id' => $this->id]);
    $sql = "delete from part_mailinglist_mailinglist where mailinglist_id =  @int(id)";
    Database::delete($sql, ['id' => $this->id]);
  }

  function getEmails() {
    $sql = "select emailaddress.object_id as id from emailaddress,person_mailinglist where emailaddress.containing_object_id=person_mailinglist.person_id and person_mailinglist.mailinglist_id =  @int(id)";
    $result = Database::select($sql, ['id' => $this->id]);
    $list = [];
    while ($row = Database::next($result)) {
      $list[] = Emailaddress::load($row['id']);
    }
    Database::free($result);
    return $list;
  }

}
?>