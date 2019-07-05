<?php
/**
 * @package OnlinePublisher
 * @subpackage Public
 */
foreach ($argv as $arg) {
  if (preg_match('/-domain=([a-z]+(\.[a-z]+)+)/', $arg, $matches)) {
    echo 'using domain: ' . $matches[1] . PHP_EOL;
    $_SERVER['SERVER_NAME'] = $matches[1];
  }
}

require_once 'Include/Public.php';

error_reporting(E_ALL);
ini_set("log_errors" , "1");
ini_set("error_log" , $basePath . "local/logs/test.log");
ini_set("display_errors" , "0");

Console::exitIfNotConsole();

echo "base : " . $basePath . "\n";

$args = Console::getArguments();
$args = array_filter($args, function($val) { return $val[0] !== '-'; });

$method = $args[1];

if (method_exists('Commander', $method)) {
  Commander::$method(array_slice($args, 2));
} else {
  $methods = get_class_methods('Commander');
  echo "Tell me what to do: " . join(', ', $methods);
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

    if (isset($args[0])) {
      $name = $args[0];
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

  static function beat() {
    HeartBeatService::beat();
  }

  static function check() {
    if (SchemaService::hasSchemaChanges()) {
      echo "error : The database schema may need correction" . PHP_EOL;
    } else {
      echo "ok : The database schema is probably correct" . PHP_EOL;
    }
    $status = InspectionService::getStatus();
    foreach ($status as $state => $count) {
      echo $state . " : " . $count . " inspections" . PHP_EOL;
    }
  }

  static function inspect($args) {
    $query = [];
    if (isset($args[0])) {
      $query['status'] = $args[0];
    }
    $results = InspectionService::performInspection($query);
    foreach ($results as $inspection) {
      //echo Strings::toJSON($inspection) . "\n";
      echo $inspection->getStatus() . " : " . $inspection->getCategory() . " : " . UI::translate($inspection->getText());
      if ($entity = $inspection->getEntity()) {
        echo " : " . $entity['type'] . "(".$entity['id'].") - " . $entity['title'];
      }
      echo "\n";
    }
  }
}
?>