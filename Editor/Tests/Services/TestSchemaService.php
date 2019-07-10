<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Services
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class TestSchemaService extends UnitTestCase {

  function testBuilding() {
    $obj = new ImagegalleryPart();

    $sql = SchemaService::buildSqlColumns(Entity::$schema['ImagegalleryPart']);
    $this->assertEqual($sql,'`variant`,`height`,`width`,`imagegroup_id`,`framed`,`frame`,`show_title`');

    $obj->setVariant('block');
    $obj->setHeight(100);
    $obj->setImageGroupId(78);
    $obj->setFramed(false);
    $obj->setShowTitle(true);

    $sql = SchemaService::buildSqlValues($obj,Entity::$schema['ImagegalleryPart']);
    $this->assertEqual($sql,"'block',100,0,78,0,'',1");


    $sql = SchemaService::buildSqlSetters($obj,Entity::$schema['ImagegalleryPart']);
    $this->assertEqual($sql,"`variant`='block',`height`=100,`width`=0,`imagegroup_id`=78,`framed`=0,`frame`='',`show_title`=1");
  }

  function testBuildCreateDatabaseSQL() {
    $table = [
      "name" => "my_table",
      "columns" => [
        "object_id" => [
          "type" => "int(11)",
          "collation" => null,
          "null" => "NO",
          "default" => null,
          "key" => "PRI",
          "extra" => "auto_increment"
        ],
        "recipe" => [
          "type" => "text",
          "collation" => "utf8_danish_ci",
          "null" => "YES",
          "default" => null,
          "key" => "",
          "extra" => ""
        ],
        "created" => [
          "type" => "datetime",
          "collation" => null,
          "null" => "NO",
          "default" => "1970-01-01 00:00:00",
          "key" => "",
          "extra" => ""
        ]
      ]
    ];
    $expected = "CREATE TABLE `my_table` (`object_id` int(11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (`object_id`), `recipe` text COLLATE utf8_danish_ci DEFAULT NULL, `created` datetime DEFAULT '1970-01-01 00:00:00' NOT NULL);";
    $sql = SchemaService::buildCreateDatabaseSQL($table);
    $this->assertEqual($sql, $expected);
  }

  function testDiffSchemas() {
    $expected = SchemaService::getExpectedDatabaseSchema()['tables'];
    $diff = SchemaService::diffSchemas($expected, $expected);
    $this->assertEqual($diff, [
      'added' => [],
      'modified' => [],
      'removed' => []
    ]);
  }

  function testDiffSchemasModifications() {
    $expected = [[
      "name" => "my_new_table",
      "columns" => [
        "object_id" => [
          "type" => "int(11)",
          "collation" => null,
          "null" => "NO",
          "default" => null,
          "key" => "",
          "extra" => ""
        ]
      ]
    ],[
      "name" => "my_modified_table",
      "columns" => [
        "modified_column" => [
          "type" => "int(11)",
          "collation" => null,
          "null" => "NO",
          "default" => 5,
          "key" => "",
          "extra" => ""
        ],
        "new_column" => [
          "type" => "int(11)",
          "collation" => null,
          "null" => "NO",
          "default" => null,
          "key" => "",
          "extra" => ""
        ],
        "unchanged_column" => [
          "type" => "int(11)",
          "collation" => null,
          "null" => "NO",
          "default" => null,
          "key" => "",
          "extra" => ""
        ]
      ]
    ]];
    $actual = [[
      "name" => "my_old_table",
      "columns" => [
        "object_id" => [
          "type" => "int(11)",
          "collation" => null,
          "null" => "NO",
          "default" => null,
          "key" => "",
          "extra" => ""
        ]
      ]
    ],[
      "name" => "my_modified_table",
      "columns" => [
        "modified_column" => [
          "type" => "int(11)",
          "collation" => null,
          "null" => "NO",
          "default" => null,
          "key" => "",
          "extra" => ""
        ],
        "old_column" => [
          "type" => "int(11)",
          "collation" => null,
          "null" => "NO",
          "default" => null,
          "key" => "",
          "extra" => ""
        ],
        "unchanged_column" => [
          "key" => "",
          "default" => null,
          "type" => "int(11)",
          "collation" => null,
          "null" => "NO",
          "extra" => ""
        ]
      ]
    ]];
    $diff = SchemaService::diffSchemas($actual, $expected);
    $this->assertEqual($diff,[
      'added' => [[
        "name" => "my_new_table",
        "columns" => [
          "object_id" => [
            "type" => "int(11)",
            "collation" => null,
            "null" => "NO",
            "default" => null,
            "key" => "",
            "extra" => ""
          ]
        ]
      ]],
      'modified' => [[
        "name" => "my_modified_table",
        "added" => [
          "new_column" => [
            "type" => "int(11)",
            "collation" => null,
            "null" => "NO",
            "default" => null,
            "key" => "",
            "extra" => ""
          ]
        ],
        "modified" => [
          "modified_column" => [
            "type" => "int(11)",
            "collation" => null,
            "null" => "NO",
            "default" => 5,
            "key" => "",
            "extra" => ""
          ]
        ],
        "removed" => [
          "old_column" => [
            "type" => "int(11)",
            "collation" => null,
            "null" => "NO",
            "default" => null,
            "key" => "",
            "extra" => ""
          ]
        ]
      ]],
      'removed' => [[
        "name" => "my_old_table",
        "columns" => [
          "object_id" => [
            "type" => "int(11)",
            "collation" => null,
            "null" => "NO",
            "default" => null,
            "key" => "",
            "extra" => ""
          ]
        ]
      ]]
    ]);
    $statements = SchemaService::statementsForDiff($diff);
    $this->assertEqual([
      'DROP TABLE `my_old_table`;',
      'CREATE TABLE `my_new_table` (`object_id` int(11) NOT NULL);',
      'ALTER TABLE `my_modified_table` DROP `old_column`, ADD `new_column` int(11) NOT NULL, CHANGE `modified_column` `modified_column` int(11) DEFAULT \'5\' NOT NULL;'
      ], $statements);
  }
}
?>