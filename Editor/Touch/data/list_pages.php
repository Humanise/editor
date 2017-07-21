<?php
require_once '../../Include/Private.php';


$list = [];

$pages = PageQuery::getRows()->asList();

foreach ($pages as $page) {
  $list[] = ['id' => $page['id'],'title' => $page['title']];
}

Response::sendObject($list);
?>