<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class SchemaService {

  static function buildSqlSetters($obj, $schema, $exclude = []) {
    $sql = '';
    $fields = $schema;
    if (isset($schema['properties'])) {
      $fields = $schema['properties'];
    }
    if (!is_array($fields)) {
      Log::debug('No fields found...');
      Log::debug($schema);
    }
    foreach ($fields as $field => $info) {
      $column = SchemaService::getColumn($field, $info);
      if (in_array($column,$exclude)) {
        continue;
      }
      if (strlen($sql) > 0) {
        $sql .= ',';
      }
      $sql .= "`" . $column . "`=";
      $getter = "get" . ucfirst($field);
      if (!method_exists($obj,$getter)) {
        Log::warn($getter . ' does not exist');
      }
      $value = $obj->$getter();
      $sql .= SchemaService::_formatValue($info['type'], $value);
    }
    return $sql;
  }

  static function buildSqlValueStructure($obj, $schema, $exclude = []) {
    $values = [];
    $fields = $schema;
    if (isset($schema['properties'])) {
      $fields = $schema['properties'];
    }
    if (!is_array($fields)) {
      Log::debug('No fields found...');
      Log::debug($schema);
      return $values;
    }
    foreach ($fields as $field => $info) {
      $column = SchemaService::getColumn($field, $info);
      if (in_array($column,$exclude)) {
        continue;
      }
      $getter = "get" . ucfirst($field);
      if (!method_exists($obj,$getter)) {
        Log::warn($getter . ' does not exist');
      }
      $value = $obj->$getter();
      $values[$column] = [$info['type'] => $value];
    }
    return $values;
  }

  static function _formatValue($type, $value) {
    if ($type == 'int') {
      return Database::int($value);
    } else if ($type == 'float') {
      return Database::float($value);
    } else if ($type == 'boolean') {
      return Database::boolean($value);
    } else if ($type == 'datetime') {
      return Database::datetime($value);
    }
    return Database::text($value);
  }

  static function getRowValue($type, $value) {
    if ($type == 'int') {
      return intval($value);
    } else if ($type == 'float') {
      return floatval($row[$column]);
    } else if ($type == 'datetime') {
      return $value ? intval($value) : null;
    } else if ($type == 'boolean') {
      return $value == 1 ? true : false;
    }
    return $value;
  }

  static function getColumn($property, $info) {
    if (isset($info['column'])) {
      return $info['column'];
    }
    return $property;
  }

  static function buildSqlColumns($schema, $exclude = []) {
    $sql = '';
    foreach ($schema['properties'] as $field => $info) {
      $column = $field;
      if (isset($info['column'])) {
        $column = $info['column'];
      }
      if (in_array($column, $exclude)) {
        continue;
      }
      if (strlen($sql) > 0) {
        $sql .= ',';
      }
      $sql .= '`' . $column . '`';
    }
    return $sql;
  }

  static function buildSqlValues($obj, $schema, $exclude = []) {
    $sql = '';
    foreach ($schema['properties'] as $field => $info) {
      $column = $field;
      if (isset($info['column'])) {
        $column = $info['column'];
      }
      if (in_array($column, $exclude)) {
        continue;
      }
      if (strlen($sql) > 0) {
        $sql .= ',';
      }
      $getter = "get" . ucfirst($field);
      if (!method_exists($obj, $getter)) {
        Log::warn($getter . ' does not exist');
      }
      $value = $obj->$getter();
      $sql .= SchemaService::_formatValue($info['type'], $value);
    }
    return $sql;
  }

  /**
   * Find all tables in the database
   * @return array Array of table names
   */
  static function getTables() {
    $config = ConfigurationService::getDatabase();
    $out = [];
    $sql = "show tables from " . $config['database'];
    $result = Database::select($sql);
    while ($row = Database::next($result)) {
      $out[] = $row[0];
    }
    Database::free($result);
    return $out;
  }

  /**
   * Find all columns of database table
   * @param string $table The name of the table
   * @return array An array of column info TODO: Format of array??
   */
  static function getTableColumns($table) {
    $config = ConfigurationService::getDatabase();
    $sql = "SHOW FULL COLUMNS FROM " . $table . " FROM " . $config['database'];
    $out = [];
    $result = Database::select($sql);
    while ($row = Database::next($result)) {
      $out[] = $row;
    }
    Database::free($result);
    return $out;
  }

  static function getDatabaseSchema() {
    $schema = ['tables' => []];

    $tables = SchemaService::getTables();

    foreach ($tables as $table) {
      $tableInfo = [];
      $tableInfo['name'] = $table;
      $columns = SchemaService::getTableColumns($table);
      $columnsInfo = [];
      foreach ($columns as $column) {
        $columnsInfo[$column['Field']] = [
          'type' => $column['Type'],
          'collation' => $column['Collation'],
          'null' => $column['Null'],
          'default' => $column['Default'],
          'key' => $column['Key'],
          'extra' => $column['Extra'],
        ];
      }

      $tableInfo['columns'] = $columnsInfo;
      $schema['tables'][] = $tableInfo;

    }

    return $schema;
  }

  static function migrate() {

    $log = [];
    $log[] = "Starting update";

    $diff = SchemaService::getSchemaDiff();
    $statements = SchemaService::statementsForDiff($diff);
    $con = Database::getConnection();

    $failed = false;
    foreach ($statements as $command) {
      $log[] = "> " . $command;
      mysqli_query($con, $command);
      $error = mysqli_error($con);
      if (strlen($error) > 0) {
        $log[] = "Error: " . $error;
        $failed = true;
      }
    }
    if (!$failed) {
      SchemaService::markSchemaAsMigrated();
    }
    $log[] = "Update finished";

    return [
      'log' => $log,
      'success' => !$failed
    ];
  }

  static function markSchemaAsMigrated() {
    $hash = md5_file(FileSystemService::getFullPath('Editor/Info/Schema.json'));
    $sql = "select `value` from `setting` where `domain`='system' and `subdomain`='database' and `key`='database-hash'";
    if ($row = Database::selectFirst($sql)) {
      $sql = "update `setting` set `value` = @text(value) where `domain`='system' and `subdomain`='database' and `key`='database-hash'";
      Database::update($sql, ['value' => $hash]);
    } else {
      $sql = "insert into `setting` (`domain`,`subdomain`,`key`,`value`) values ('system','database','database-hash',@text(value))";
      Database::insert($sql, ['value' => $hash]);
    }
  }

  /**
   * Checks whether the schema file has been changed
   * @return boolean True if the database is up to date
   */
  static function hasSchemaChanges() {
    $hash = md5_file(FileSystemService::getFullPath('Editor/Info/Schema.json'));
    $sql = "select `value` from `setting` where `domain`='system' and `subdomain`='database' and `key`='database-hash' and `value` = @text(hash)";
    return Database::isEmpty($sql, ['hash' => $hash]);
  }

  static function getExpectedDatabaseSchema() {
    $file = FileSystemService::getFullPath('Editor/Info/Schema.json');
    return JsonService::readFile($file, true);
  }

  static function getSchemaDiff() {
    $actualTables = SchemaService::getDatabaseSchema()['tables'];
    $expected = SchemaService::getExpectedDatabaseSchema()['tables'];
    //Log::debug($expected);
    //Log::debug($actualTables);
    return SchemaService::diffSchemas($actualTables, $expected);
  }

  static function diffSchemas($actualTables, $expectedTables) {
    $diff = [
      'added' => [],
      'modified' => [],
      'removed' => []
    ];
    foreach ($expectedTables as $expectedTable) {
      $actualTable = null;
      foreach ($actualTables as $table) {
        if ($table['name'] == $expectedTable['name']) {
          $actualTable = $table;
          break;
        }
      }
      if ($actualTable) {
        foreach ($expectedTable as $columnName => $expConfig) {
          if ($actCol = $actualTable[$columnName]) {
            //array_diff();
          } else {
            // A column is added
          }
        }
      } else {
        $diff['added'][] = $expectedTable;
      }
    }
    foreach ($actualTables as $actualTable) {
      $expectedTable = null;
      foreach ($expectedTables as $table) {
        if ($table['name'] == $actualTable['name']) {
          $expectedTable = $table;
        }
      }
      if (!$expectedTable) {
        $diff['removed'][] = $actualTable;
      } else {
        if ($tableDiff = SchemaService::diffTables($actualTable, $expectedTable)) {
          $diff['modified'][] = array_merge(['name' => $expectedTable['name']], $tableDiff);
        }
      }
    }
    return $diff;
  }

  static function diffTables($actualTable, $expectedTable) {
    $actualNames = array_keys($actualTable['columns']);
    $expectedNames = array_keys($expectedTable['columns']);
    $added = [];
    foreach (array_diff($expectedNames, $actualNames) as $name) {
      $added[$name] = $expectedTable['columns'][$name];
    }
    $removed = [];
    foreach (array_diff($actualNames, $expectedNames) as $name) {
      $removed[$name] = $actualTable['columns'][$name];
    }
    $modified = [];
    foreach ($actualNames as $columnName) {
      if (in_array($columnName, $expectedNames)) {
        $actualColumn = $actualTable['columns'][$columnName];
        $expectedColumn = $expectedTable['columns'][$columnName];
        ksort($actualColumn);
        ksort($expectedColumn);
        if ($actualColumn !== $expectedColumn) {
          $modified[$columnName] = $expectedColumn;
        }
      }
    }
    if (!$added && !$modified && !$removed) {
      return null;
    }
    $diff = [
      'added' => $added,
      'modified' => $modified,
      'removed' => $removed
    ];
    return $diff;
  }

  static function statementsForDiff($diff) {
    $statements = [];
    foreach ($diff['removed'] as $table) {
      $statements[] = 'DROP TABLE `' . $table['name'] . '`;';
    }
    foreach ($diff['added'] as $table) {
      $statements[] = SchemaService::buildCreateDatabaseSQL($table);
    }
    foreach ($diff['modified'] as $modifications) {
      $parts = [];
      foreach ($modifications['removed'] as $name => $config) {
        $parts[] = 'DROP `' . $name . '`';
      }
      foreach ($modifications['added'] as $name => $properties) {
        $parts[] = 'ADD ' . SchemaService::buildColumnSQL($name, $properties);
      }
      foreach ($modifications['modified'] as $name => $properties) {
        $parts[] = 'CHANGE `' . $name . '` ' . SchemaService::buildColumnSQL($name, $properties);
      }
      $statements[] = 'ALTER TABLE `' . $modifications['name'] . '` ' . join($parts,', ') . ';';
    }
    return $statements;
  }

  static function buildCreateDatabaseSQL($table) {
    $columnSql = [];
    $primaryKey = null;
    foreach ($table['columns'] as $columnName => $props) {
      $columnSql[] = SchemaService::buildColumnSQL($columnName, $props);
      if ($props['key'] == 'PRI') {
        $columnSql[] = 'PRIMARY KEY (`' . $columnName . '`)';
      }
    }
    $sql = 'CREATE TABLE `' . $table['name'] . '` (' . join($columnSql,', ') . ');';
    return $sql;
  }

  static function buildColumnSQL($name, $properties) {
    $sql = ['`' . $name . '`'];
    $sql[] = $properties['type'];
    if ($properties['collation']) {
      $sql[] = 'COLLATE ' . $properties['collation'];
    }
    if ($properties['default'] === null) {
      if ($properties['null'] != 'NO') {
        $sql[] = 'DEFAULT NULL';
      }
    } else {
      $sql[] = 'DEFAULT \'' . $properties['default'] . '\'';
    }
    if ($properties['null'] == 'NO') {
      $sql[] = 'NOT NULL';
    }
    if ($properties['extra'] == 'auto_increment') {
      $sql[] = 'AUTO_INCREMENT';
    }
    return join($sql,' ');
  }

}