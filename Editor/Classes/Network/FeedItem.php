<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Network
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class FeedItem {
  var $title;
  var $description;
  var $link;
  var $pubDate;
  var $guid;
  var $enclosures = [];

  function addEnclosure($url,$type,$length) {
    $this->enclosures[] = ['url' => $url, 'type' => $type, 'length' => $length];
  }

  function getEnclosures() {
    return $this->enclosures;
  }

  function setTitle($title) {
      $this->title = $title;
  }

  function getTitle() {
      return $this->title;
  }

  function setDescription($description) {
      $this->description = $description;
  }

  function getDescription() {
      return $this->description;
  }

  function setLink($link) {
      $this->link = $link;
  }

  function getLink() {
      return $this->link;
  }

  function setPubDate($pubDate) {
      $this->pubDate = $pubDate;
  }

  function getPubDate() {
      return $this->pubDate;
  }

  function setGuid($guid) {
      $this->guid = $guid;
  }

  function getGuid() {
      return $this->guid;
  }

}
?>