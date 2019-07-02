<?php
/**
 * @package OnlinePublisher
 * @subpackage Public
 */
require_once 'Include/Public.php';

error_reporting(E_ALL);
ini_set("log_errors" , "1");
ini_set("error_log" , $basePath . "local/logs/test.log");
ini_set("display_errors" , "0");

Console::exitIfNotConsole();

echo "base : " . $basePath . "\n";

$args = Console::getArguments();

if (method_exists('Commander',$args[1])) {
  $method = $args[1];
  Commander::$method($args);
} else {
  $methods = get_class_methods('Commander');
  echo "Tell me what to do: " . join(', ',$methods);
  echo "\n: ";
  $handle = fopen ("php://stdin","r");
  $cmd = trim(fgets($handle));
  if (method_exists('Commander',$cmd)) {
    Commander::$cmd();
  } else {
    echo "No action: " . $cmd;
  }
}


class Commander {

  static function test($args) {
    if (!Database::testConnection()) {
      echo "No database - no testing!\n";
      exit;
    }

    if (isset($args[2])) {
      $name = $args[2];
      if (strpos($name,'/') !== false) {
        TestService::runTest($name,new ConsoleReporter());
      } else {
        if (strpos($name, 'Test') === 0) {
          echo "Runnig single test: $name\n";
          TestService::runTestByName($name,new ConsoleReporter());
        } else {
          TestService::runTestsInGroup($name,new ConsoleReporter());
        }
      }
    } else {
      TestService::runAllTests(new ConsoleReporter());
    }
  }

  static function hui() {
    echo UI::compile();
  }

  static function schema() {
    global $basePath;
    $schema = SchemaService::getDatabaseSchema();

    $schema_code = var_export($schema,true);

    $file = $basePath . "Editor/Info/Schema.php";

    $data = "<?php
/**
 * @package OnlinePublisher
 * @subpackage Info
 */

if (!isset(\$GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}
\$HUMANISE_EDITOR_SCHEMA = " . $schema_code . "
?>";
    FileSystemService::writeStringToFile($data,$file);
    FileSystemService::writeStringToFile(json_encode($schema, JSON_PRETTY_PRINT),$basePath . "Editor/Info/Schema.json");
    echo json_encode($schema, JSON_PRETTY_PRINT) . PHP_EOL;
  }

  static function classes() {
    $success = ClassService::rebuildClasses();
    echo $success ? 'Classes successfully rebuild' : 'ERROR: Classes could not be rebuild';
    echo PHP_EOL;
  }

  static function diff() {
    $diff = SchemaService::getSchemaDiff();
    //echo Strings::toJSON($diff) . "\n";
    $statements = SchemaService::statementsForDiff($diff);
    foreach ($statements as $sql) {
      echo $sql . "\n";
    }
  }

  static function migrate() {
    $result = SchemaService::migrate();
    foreach ($result['log'] as $line) {
      echo $line . "\n";
    }
  }

  static function check() {
    if (SchemaService::hasSchemaChanges()) {
      echo "The database schema may need correction" . PHP_EOL;
    } else {
      echo "The database schema is probably correct" . PHP_EOL;
    }
  }

  static function full() {
    Commander::classes();
    Commander::hui();
  }
}
?>