<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.General
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class TestDatabase extends UnitTestCase {

  function testSqlBuilding() {
    $query = [
      'table' => 'frame',
      'values' => [
        'title' => Database::text('my title'),
        'name' => Database::text('my name'),
        'hierarchy_id' => Database::int('9')
      ],
      'where' => [ 'id' => 89 ]
    ];

    $sql = Database::buildUpdateSql($query);
    $this->assertEqual($sql,"update frame set `title`='my title',`name`='my name',`hierarchy_id`=9 where `id`=89");

    $sql = Database::buildInsertSql($query);
    $this->assertEqual($sql,"insert into frame (`title`,`name`,`hierarchy_id`) values ('my title','my name',9)");
  }

  function testSafeSqlBuilding() {
    $query = [
      'table' => 'frame',
      'values' => [
        'title' => ['text' => 'my title'],
        'name' => ['text' => 'my name'],
        'selected' => ['boolean' => true],
        'hierarchy_id' => ['int' => 9]
      ],
      'where' => [ 'id' => ['int' => 89] ]
    ];

    $sql = Database::buildUpdateSql($query);
    $this->assertEqual($sql,"update frame set `title`='my title',`name`='my name',`selected`=1,`hierarchy_id`=9 where `id`=89");

    $sql = Database::buildInsertSql($query);
    $this->assertEqual($sql,"insert into frame (`title`,`name`,`selected`,`hierarchy_id`) values ('my title','my name',1,9)");
  }

  function testCompiling() {
    $sql = "SELECT * from table where id=@int(id) or id>@int(id) and index=@text(query) and date>@datetime(date)";
    $parameters = ['id' => 5355, 'query' => 'lorem', 'date' => 123456];
    $expected = "SELECT * from table where id=5355 or id>5355 and index='lorem' and date>'1970-01-02 11:17:36'";

    $compiled = Database::compile($sql,$parameters);
    $this->assertEqual($expected,$compiled);
  }
}