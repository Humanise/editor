<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Templates
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}
class SearchTemplate {

  static $TYPES = ['pages' => 'Sider', 'images' => 'Billeder', 'files' => 'Filer', 'news' => 'Nyheder', 'products' => 'Produkter', 'persons' => 'Personer'];
  static $PARTS = ['label' => 'text', 'enabled' => 'boolean', 'default' => 'boolean', 'hidden' => 'boolean'];

  var $id;
  var $title;
  var $text;

  var $pagesLabel;
  var $pagesEnabled;
  var $pagesDefault;
  var $pagesHidden;

  var $imagesLabel;
  var $imagesEnabled;
  var $imagesDefault;
  var $imagesHidden;

  var $filesLabel;
  var $filesEnabled;
  var $filesDefault;
  var $filesHidden;

  var $newsLabel;
  var $newsEnabled;
  var $newsDefault;
  var $newsHidden;

  var $productsLabel;
  var $productsEnabled;
  var $productsDefault;
  var $productsHidden;

  var $personsLabel;
  var $personsEnabled;
  var $personsDefault;
  var $personsHidden;


  function setId($id) {
      $this->id = $id;
  }

  function getId() {
      return $this->id;
  }

  function setTitle($title) {
      $this->title = $title;
  }

  function getTitle() {
      return $this->title;
  }

  function setText($text) {
      $this->text = $text;
  }

  function getText() {
      return $this->text;
  }


  function save() {
    $query = [
      'table' => 'search',
      'values' => [
        'title' => ['text' => $this->title],
        'text' => ['text' => $this->text]
      ],
      'where' => [ 'page_id' => ['int' => $this->id] ]
    ];

    foreach (SearchTemplate::$TYPES as $type => $label) {
      foreach (SearchTemplate::$PARTS as $part => $kind) {
        $method = $type . ucfirst($part);
        $query['values'][$type . $part] = [$kind => $this->$method];
      }
    }
    Database::update($query);

    PageService::markChanged($this->id);
  }

  static function load($id) {
    $sql = "select * from search where page_id = @id";
    if ($row = Database::selectFirst($sql, $id)) {
      $obj = new SearchTemplate();
      $obj->setId(intval($row['page_id']));
      $obj->setTitle($row['title']);
      $obj->setText($row['text']);

      foreach (SearchTemplate::$TYPES as $type => $label) {
        foreach (SearchTemplate::$PARTS as $part => $kind) {
          $method = $type . ucfirst($part);
          $obj->$method = $kind == 'boolean' ? ($row[$type . $part] ? true : false) : $row[$type . $part];
        }
      }
      return $obj;
    }
    return null;
  }
}