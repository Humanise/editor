<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Modules.Testing
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class ConsoleReporter extends SimpleReporter {

  private $failedTests = [];

  /**
   *    Paints the title only.
   *    @param string $test_name        Name class of test.
   *    @access public
   */
  function paintHeader($test_name) {
    echo "$test_name\n";
    flush();
  }

  /**
   *    Paints the end of the test with a summary of
   *    the passes and failures.
   *    @param string $test_name        Name class of test.
   *    @access public
   */
  function paintFooter($test_name) {
    if ($this->getFailCount() + $this->getExceptionCount() == 0) {
      print "OK\n";
    } else {
      print "FAILURES!!!\n";
    }
    print "Test cases run: " . $this->getTestCaseProgress() .
            "/" . $this->getTestCaseCount() .
            ", Passes: " . $this->getPassCount() .
            ", Failures: " . $this->getFailCount() .
            ", Exceptions: " . $this->getExceptionCount() . "\n";
    if (count($this->failedTests) > 0) {
      print "Failed tests: " . implode(',', $this->failedTests) . "\n";
    }
  }

  function paintCaseStart($test_name) {
    parent::paintCaseStart($test_name);
    echo 'Running... ' . $test_name . "\n";
  }

  function paintMethodStart($test_name) {
    parent::paintMethodStart($test_name);
    echo ' · ' . $test_name . "\n";
  }

  /**
   *    Paints the test failure as a stack trace.
   *    @param string $message        Failure message displayed in
   *                           the context of the other tests.
   *    @access public
   */
  function paintFail($message) {
    parent::paintFail($message);
    print $this->getFailCount() . ") $message\n";
    $breadcrumb = $this->getTestList();
    $testName = $breadcrumb[2];
    if (!in_array($testName, $this->failedTests)) {
      $this->failedTests[] = $testName;
    }
    array_shift($breadcrumb);
    print "\tin " . implode("\n\tin ", array_reverse($breadcrumb));
    print "\n";
  }

  /**
   *    Paints a PHP error or exception.
   *    @param string $message        Message is ignored.
   *    @access public
   *    @abstract
   */
  function paintException($message) {
    parent::paintException($message);
    print "Exception " . $this->getExceptionCount() . "!\n$message\n";
  }

  /**
   *    Paints formatted text such as dumped variables.
   *    @param string $message        Text to show.
   *    @access public
   */
  function paintFormattedMessage($message) {
    print "$message\n";
    flush();
  }
}
?>