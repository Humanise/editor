<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Preview
 */
require_once '../../../../Include/Private.php';

$id = Request::getInt('id');

$page = Page::load($id);

$arr = [
  'title'=>$page->getTitle(),
  'path'=>$page->getPath(),
  'keywords'=>$page->getKeywords(),
  'language'=>$page->getLanguage(),
  'description'=>$page->getDescription()
];

Response::sendObject($arr);
?>