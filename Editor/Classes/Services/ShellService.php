<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class ShellService {

  static function execute($cmd) {
    $path = ConfigurationService::getShellPath();
    if (!empty($path)) {
      $cmd = "export PATH=\"" . $path . "\"; " . $cmd;
    }
    return shell_exec($cmd);
  }

  static function executeLive($cmd) {
      while (@ ob_end_flush()); // end all output buffers if any

      $proc = popen("$cmd 2>&1 ; echo Exit status : $?", 'r');

      $live_output = "";
      $complete_output = "";

      while (!feof($proc)) {
        $live_output = fread($proc, 4096);
        $complete_output = $complete_output . $live_output;
        echo "$live_output";
        @ flush();
      }

      pclose($proc);

      // get exit status
      preg_match('/[0-9]+$/', $complete_output, $matches);

      // return exit status and intended output
      return  [
        'status' => intval($matches[0]),
        'output' => str_replace("Exit status : " . $matches[0], '', $complete_output)
      ];
  }
}