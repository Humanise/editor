<?php
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class SystemInfo {

  private static $date = 14;
  private static $month = 9;
  private static $year = 2020;
  private static $feedbackMail = "jonasmunk@mac.com";
  private static $feedbackName = "Jonas Munk";

  static function getDate() {
    return mktime(0,0,0,SystemInfo::$month,SystemInfo::$date,SystemInfo::$year);
  }

  static function getFormattedDate() {
    return Dates::formatDate(SystemInfo::getDate());
  }

  static function getTitle() {
    return 'OnlinePublisher ' . SystemInfo::getFormattedDate();
  }

  static function getFeedbackMail() {
    return SystemInfo::$feedbackMail;
  }

  static function getFeedbackName() {
    return SystemInfo::$feedbackName;
  }
}
?>