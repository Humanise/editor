<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Core
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}
class Database {

  static function testConnection() {
    try {
      if (!function_exists('mysqli_connect')) {
        return false;
      }
      return Database::getConnection() !== false;
    } catch (mysqli_sql_exception $e) {
      error_log($e);
      return false;
    }
  }

  static function testServerConnection($host,$user,$password) {
    if (!function_exists('mysqli_connect')) {
      return false;
    }

    $con = mysqli_connect($host, $user,$password);
    if (!$con) {
      return false;
    }
    if (mysqli_errno($con) > 0) {
      return false;
    }
    return true;
  }

  static function testDatabaseConnection($host,$user,$password,$name) {
    $con = mysqli_connect($host, $user,$password);
    if (!$con) {
      return false;
    }
    mysqli_select_db($con,$name);
    if (mysqli_errno($con) > 0) {
      return false;
    }
    return true;
  }

  static function debug($sql) {
    if (isset($_SESSION['core.debug.logDatabaseQueries']) && $_SESSION['core.debug.logDatabaseQueries']) {
      error_log($sql);
    }
  }

  static function getConnection() {
    if (!isset($GLOBALS['OP_CON'])) {
      $config = ConfigurationService::getDatabase();
      $con = mysqli_connect($config['host'], $config['user'],$config['password'],false);
      if (!$con) {
        return false;
      }
      // TODO mysqli_set_charset is expensive - and sometimes it has no effect (it is correct already)
      if (ConfigurationService::isUnicode()) {
        mysqli_set_charset($con,'utf8');
      } else {
        mysqli_set_charset($con,'latin1');
      }
      mysqli_select_db($con,$config['database']);
      if (mysqli_errno($con) > 0) {
        return false;
      }
      //$con->query("SET timezone = 'UTC'");
      $GLOBALS['OP_CON'] = $con;
    }
    return $GLOBALS['OP_CON'];
  }

  static function select($sql,$parameters = null) {
    $con = Database::getConnection();
    if (!$con) {
      error_log('No database connection');
      return false;
    }
    if ($parameters !== null) {
      $sql = Database::compile($sql,$parameters);
    }
    Database::debug($sql);
    $result = mysqli_query($con,$sql);
    if (mysqli_errno($con) > 0) {
      error_log(mysqli_error($con) . ': ' . $sql);
      return false;
    }
    else {
      return $result;
    }
  }

  static function selectFirst($sql,$parameters = null) {
    $output = null;
    $result = Database::select($sql, $parameters);
    if ($row = Database::next($result)) {
      $output = $row;
    }
    Database::free($result);
    return $output;
  }

  static function selectAll($sql,$parameters = null) {
    $output = [];
    $result = Database::select($sql, $parameters);
    while ($row = Database::next($result)) {
      $output[] = $row;
    }
    Database::free($result);
    return $output;
  }

  static function selectAllKeys($sql, $key,$parameters = null) {
    $output = [];
    $result = Database::select($sql, $parameters);
    while ($row = Database::next($result)) {
      $output[$row['key']] = $row;
    }
    Database::free($result);
    return $output;
  }

  static function size($result) {
    return mysqli_num_rows($result);
  }

  static function isEmpty($sql,$parameters = null) {
    $output = true;
    $result = Database::select($sql,$parameters);
    if (Database::next($result)) {
      $output = false;
    }
    Database::free($result);
    return $output;
  }

  static function update($sql,$parameters = null) {
    if (is_array($sql)) {
      $sql = Database::buildUpdateSql($sql);
    }
    if ($parameters !== null) {
      $sql = Database::compile($sql, $parameters);
    }
    $con = Database::getConnection();
    mysqli_query($con, $sql);
    return Database::_checkError($sql,$con);
  }

  static function delete($sql,$parameters = null) {
    if ($parameters !== null) {
      $sql = Database::compile($sql, $parameters);
    }
    Database::debug($sql);
    $con = Database::getConnection();
    mysqli_query($con, $sql);
    Database::_checkError($sql, $con);
    return mysqli_affected_rows($con);
  }

  static function insert($sql,$parameters = null) {
    if (is_array($sql)) {
      $sql = Database::buildInsertSql($sql);
    }
    if ($parameters !== null) {
      $sql = Database::compile($sql, $parameters);
    }
    Database::debug($sql);
    $con = Database::getConnection();
    mysqli_query($con, $sql);
    $id = mysqli_insert_id($con);
    if (Database::_checkError($sql, $con)) {
      return $id;
    } else {
      return false;
    }
  }

  static function _checkError($sql,&$con) {
    if (mysqli_errno($con) > 0) {
      error_log(mysqli_error($con) . ': ' . $sql);
      return false;
    }
    else {
      return true;
    }
  }

  static function next($result) {
    return mysqli_fetch_array($result);
  }

  static function free($result) {
    mysqli_free_result($result);
  }

  static function selectArray($sql) {
    $result = Database::select($sql);
    $ids = [];
    while($row = Database::next($result)) {
      $ids[] = $row[0];
    }
    Database::free($result);
    return $ids;
  }

  static function selectMap($sql, $parameters = null) {
    $result = Database::select($sql, $parameters);
    $map = [];
    while($row = Database::next($result)) {
      $map[$row[0]] = $row[1];
    }
    Database::free($result);
    return $map;
  }

  static function selectIntArray($sql, $parameters = null) {
    $result = Database::select($sql, $parameters);
    $ids = [];
    while($row = Database::next($result)) {
      $ids[] = intval($row[0]);
    }
    Database::free($result);
    return $ids;
  }

  static function getIds($sql, $parameters = null) {
    if ($parameters !== null) {
      $sql = Database::compile($sql, $parameters);
    }
    $result = Database::select($sql);
    $ids = [];
    while($row = Database::next($result)) {
      $ids[] = intval($row['id']);
    }
    Database::free($result);
    return $ids;
  }

  static function buildArray($sql) {
    $result = Database::select($sql);
    $ids = [];
    while($row = Database::next($result)) {
      $ids[] = $row[0];
    }
    Database::free($result);
    return $ids;
  }

  /**
   * Formats a string of text for use in an SQL-sentence
   * @param string $text The text to format
   * @return string The formattet string
   */
  static function text($text) {
    if ($text == NULL) {
      $text = '';
    }
    return "'" . mysqli_real_escape_string(Database::getConnection(),$text) . "'";
  }

  /**
   * Formats an integer for use in an SQL-sentence
   * @param int $value The number to format
   * @return string The formatet string
   */
  static function int($value) {
    return intval($value);
  }

  static function ints($value) {
    $out = "(";
    if (is_array($value)) {
      for ($i=0; $i < count($value); $i++) {
        if (is_numeric($value[$i])) {
          if ($out !== "(") $out .= ',';
          $out .= intval($value[$i]);
        }
      }
    }
    $out .= ")";
    return $out;
  }

  /**
   * Formats an float for use in an SQL-sentence
   * @param float $value The number to format
   * @return string The formatet string
   */
  static function float($value) {
    return floatval($value);
  }

  /**
   * Formats a boolean for use in an SQL-sentence
   * @param boolean $bool The boolean to format
   * @return string The formattet string
   */
  static function boolean($bool) {
    if ($bool) {
      return '1';
    } else {
      return '0';
    }
  }

  /**
   * Formats a unix timestamp for use in an SQL-sentence
   * @param int $stamp The number to format
   * @return string The formattet string
   */
  static function datetime($stamp) {
    if (is_numeric($stamp)) {
      return "FROM_UNIXTIME(" . intval($stamp) . ")";
    }
    else {
      return "NULL";
    }
  }

  /**
   * Formats a unix timestamp for use in an SQL-sentence
   * @param int $stamp The number to format
   * @return string The formattet string
   */
  static function date($stamp) {
    if (is_numeric($stamp)) {
      return "'" . gmdate('Y-m-d',intval($stamp)) . "'";
    }
    else {
      return "NULL";
    }
  }

  /**
   * Formats a string for use as a search parameter in an SQL-sentence
   * @param string $text The text to format
   * @return string The formattet string
   */
  static function search($text) {
    if ($text == NULL) {
      $text = '';
    }
    return "'%" . str_replace('%','\%',mysqli_real_escape_string(Database::getConnection(),$text)) . "%'";
  }

  static function buildUpdateSql($arr) {
    $sql = "update " . $arr['table'] . " set ";
    $num = 0;
    foreach ($arr['values'] as $column => $value) {
      if ($num > 0) {
        $sql .= ',';
      }
      $sql .= "`" . $column . "`=" . Database::buildUpdateSqlValue($value);
      $num++;
    }
    $sql .= " where ";
    $num = 0;
    foreach ($arr['where'] as $column => $value) {
      if ($num > 0) {
        $sql .= ' and ';
      }
      $sql .= "`" . $column . "`=" . Database::buildUpdateSqlValue($value);
    }
    return $sql;
  }

  static function buildUpdateSqlValue($value) {
    if (is_array($value)) {
      if (array_key_exists('text', $value)) {
        return Database::text($value['text']);
      }
      elseif (array_key_exists('string', $value)) {
        return Database::text($value['string']);
      }
      else if (array_key_exists('int', $value)) {
        return Database::int($value['int']);
      }
      else if (array_key_exists('float', $value)) {
        return Database::float($value['float']);
      }
      else if (array_key_exists('boolean', $value)) {
        return Database::boolean($value['boolean']);
      }
      else if (array_key_exists('datetime', $value)) {
        return Database::datetime($value['datetime']);
      }
      Log::warn('Invalid value: ' . $value);
      return "ERROR";
    }
    return $value;
  }

  static function buildInsertSql($arr) {
    $sql = "insert into " . $arr['table'] . " (";
    $num = 0;
    foreach ($arr['values'] as $column => $value) {
      if ($num > 0) {
        $sql .= ',';
      }
      $sql .= "`" . $column . "`";
      $num++;
    }
    $sql .= ") values (";
    $num = 0;
    foreach ($arr['values'] as $column => $value) {
      if ($num > 0) {
        $sql .= ',';
      }
      $sql .= Database::buildUpdateSqlValue($value);
      $num++;
    }
    $sql .= ")";
    return $sql;
  }

  static function buildDeleteSql($arr) {
    $sql = "delete from " . $arr['table'];
    $sql .= " where ";
    $num = 0;
    foreach ($arr['where'] as $column => $value) {
      if ($num > 0) {
        $sql .= ' and ';
      }
      $sql .= "`" . $column . "`=" . Database::buildUpdateSqlValue($value);
    }
    return $sql;
  }

  static function compile($sql, $vars) {
    if (!is_array($vars)) {
      $vars = ['id' => $vars];
    }
    $replacements = [];
    if (preg_match_all("/@[a-z]+\\([a-zA-Z]+\\)|@id/u", $sql,$matches) > 0) {
      foreach ($matches[0] as $expression) {
        if ($expression == '@id') {
          $type = 'int';
          $name = 'id';
        } else {
          $pos = strpos($expression,'(');
          $type = substr($expression, 1, $pos - 1);
          $name = substr($expression, $pos + 1, -1);
        }
        if (array_key_exists($name,$vars)) {
          $value = $vars[$name];
          if ($type == 'int') {
            $value = Database::int($value);
          } else if ($type == 'ints') {
            $value = Database::ints($value);
          } else if ($type == 'text') {
            $value = Database::text($value);
          } else if ($type == 'fuzzy') {
            $value = Database::search($value);
          } else if ($type == 'boolean') {
            $value = Database::boolean($value);
          } else if ($type == 'datetime') {
            $value = Database::datetime($value);
          } else if ($type == 'float') {
            $value = Database::float($value);
          } else if ($type == 'name') {
            $value = '`' . $value . '`';
          } else {
            continue;
          }
          $replacements[$expression] = $value;
        }
      }
    }
    return Strings::replace($sql, $replacements);
  }
}
?>